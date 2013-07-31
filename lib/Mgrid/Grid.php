<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://mgrid.mdnsolutions.com/license>.
 */

namespace Mgrid;

/**
 * Abstract class to generate the grid components
 *
 * @since       0.0.1
 * @author      Renato Medina <medinadato@gmail.com>
 */
abstract class Grid
{

    /**
     * @var string html id used by grid
     */
    protected $id;

    /**
     * @var Grid\Source\ISource source of the grid
     */
    protected $source;

    /**
     * @var array columns used by grid
     */
    protected $columns = array();

    /**
     * @var boolean it is set by the columns when at least one of them has filter
     */
    protected $hasFilter = false;

    /**
     * @var boolean if the grid should order results, by default yes
     */
    protected $hasOrdering = true;
    
    /**
     * @var boolean if the grid should have pager
     */
    protected $hasPager;
    
    /**
     * @var boolean if the grid should have export
     */
    protected $hasExport;
    
    /**
     * @var boolean if the grid should have mass actions
     */
    protected $hasMassAction;
    
    /**
     * @var boolean if the grid should have table headers
     */
    protected $hasHeaders;

    /**
     * @var array  
     */
    protected $resultSet;

    /**
     *
     * @var int 
     */
    protected $numRecords;

    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @var \Mgrid\Session 
     */
    protected $sessionHandle;

    /**
     * @var \Mgrid\Filter
     */
    protected $filterHandle;
    
    /**
     * @var \Mgrid\Order
     */
    protected $orderHandle;

    /**
     * @var \Mgrid\Pager
     */
    protected $pagerHandle;

    /**
     * @var $_REQUEST 
     */
    protected $request;

    /**
     * Load the basic configuration
     */
    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/templates/default/');

        // load twig
        $this->twig = new \Twig_Environment($loader);

        // set session handle
        $this->sessionHandle = new \Mgrid\Session;

        // set filter handle
        $this->filterHandle = new \Mgrid\Filter;
        
        // set ordering handle
        $this->orderHandle = new \Mgrid\Order;

        // set pager handle
        $this->pagerHandle = new \Mgrid\Pager;

        // set request
        $this->setRequest($_REQUEST);

        // run init
        $this->init();
    }

    /**
     * Returns the HTML output
     */
    public function render()
    {
        $this->build();

        return $this->twig->render('grid.html.twig', array(
                    'grid'          => $this,
                    'pager'         => $this->pagerHandle->getPager(),
                        )
        );
    }

    /**
     * Generates the settings
     *
     * @return Grid
     */
    private function build()
    {
        // generate the grid
        $this->processSource()
                ->processFilter()
                ->processOrder()
                ->processPager();

        return $this;
    }

    /**
     * Set the result set array
     */
    private function processSource()
    {
        $this->setResultSet($this->getSource()->execute());

        return $this;
    }

    /**
     * Build user defined filters
     *
     * @return Grid
     */
    private function processFilter()
    {
        // set grid with filter or not
        foreach ($this->getColumns() as $column) {
            if ($column->hasFilter()) {
                $this->setHasFilter(true);
            }
        }

        // sent parameters
        $params = $this->getRequest();

        // remove filters
        if (isset($params['mgrid']['removeFilter'])) {
            $this->sessionHandle->unsetData('filters');
            return $this;
        }

        // add valid filters
        if (isset($params['mgrid']['addFilter'])) {
            $this->sessionHandle->unsetData('filters');
        }

        // there is parametes into the session
        if ($this->sessionHandle->hasParam('filters')) {
            $params['mgrid'] = $this->sessionHandle->getData('filters');
        }

        // case no params for filter
        if (!isset($params['mgrid']['filter'])) {
            return $this;
        }

        foreach ($params['mgrid']['filter'] AS $key => $value) {

            if (is_array($value)) {
                foreach ($value as $subKey => $subVal) {
                    if (empty($subVal)) {
                        continue 1;
                    }

                    $this->filterHandle->addFilter(array($key => array($subKey => $subVal)));
                }
            } elseif (!empty($value)) {
                $this->filterHandle->addFilter($key, $value);
            }
        }

        // add filters to session
        $this->sessionHandle->setData('filters', $params['mgrid']);

        // apply filters on RS
        $this->setResultSet($this->filterHandle->apply($this->getColumns(), $this->getResultSet()));

        return $this;
    }
    
    /**
     * Process the ordering for columns
     * 
     * @return \Mgrid\Grid
     */
    private function processOrder()
    {
        if (!$this->hasOrdering()) {
            return $this;
        }
        
        // apply the sorting
        $sortedResultSet = $this->orderHandle->apply($this->getColumns(), $this->getResultSet(), $this->getRequest());
        $this->setResultSet($sortedResultSet);

        return $this;
    }

    /**
     * apply pager on RS
     */
    protected function processPager()
    {
        $this->setResultSet($this->pagerHandle->apply($this->getResultSet(), $this->getRequest()));

        return $this;
    }

    /**
     * Add a new column
     *
     * $column may be either an array column options, or an object of type
     * Mgrid\Column. 
     *
     * @param  array|Mgrid\Column $column
     * @throws Mgrid\Exception on invalid element
     * @return Grid
     */
    public function addColumn($column)
    {
        if (is_array($column)) {
            $options = $column;

            if (null === $options['index'])
                throw new Grid\Exception('Columns specified by array must have an accompanying index');

            $this->columns[] = $this->createColumn($options);
        } elseif ($column instanceof \Mgrid\Column) {

            if (null === $column->getIndex())
                throw new Grid\Exception('Columns must have an accompanying index');

            $this->columns[$column->getIndex()] = $element;
        } else {
            throw new Grid\Exception('Column must be specified by array options or Mgrid\Column instance');
        }

        return $this;
    }

    /**
     * Acts as a factory for creating column. Columns created with this
     * method will not be attached to the grid, but will contain column
     * settings as specified in the grid object (including plugin render
     * ordering, filter, etc.).
     * 
     * @param array $options
     * @return \Mgrid\Column
     */
    public function createColumn(array $options)
    {
        return new \Mgrid\Column($options);
    }

    /**
     * Adds an user action to the grid
     * 
     * @param mixed $action
     * @return \Mgrid\Grid
     * @throws \Mgrid\Exception
     */
    public function addAction($action)
    {
        if (is_array($action)) {
            $action = new \Mgrid\Action($action);
        }
        
        if (!($action instanceof \Mgrid\Action)) {
            throw new \Mgrid\Exception('Invalid action param');
        }

        $this->actions[] = $action;
        
        return $this;
    }

    /**
     * returns actions
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * returns only actions that attends to self condition
     * @param array $row
     * @return array
     */
    public function getActionsByRow(array $row)
    {
        $tmpActions = array();
        $actions = $this->getActions();
        foreach ($actions as $action)
            if ($action->attendToRowCondition($row))
                $tmpActions[] = $action;
        return $tmpActions;
    }

    public function getShowActions()
    {
        return (count($this->getActions()) && $this->showActions);
    }

    /**
     * @param Mgrid\Source\SourceInterface $source
     * @return Grid 
     */
    public function setSource(\Mgrid\Source\SourceInterface $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return Grid\Source\ISource
     */
    public function getSource()
    {
        if ($this->source === null) {
            throw new \Mgrid\Exception('Please specify your source');
        }

        return $this->source;
    }

    /**
     * 
     * @param array $resultSet
     * @return \Mgrid\Grid
     */
    public function setResultSet($resultSet)
    {

        $this->resultSet = $resultSet;
        $this->setNumRecords(count($resultSet));

        return $this;
    }

    /**
     * 
     * @return array
     */
    public function getResultSet()
    {
        if ($this->resultSet === false) {
            throw new \Mgrid\Exception('You must build the grid before get the result. Use the method build()');
        }
        return $this->resultSet;
    }

    /**
     * Returns the columns of the grid
     * @return array
     */
    public function getColumns()
    {
        if (count($this->columns) == 0) {
            throw new \Mgrid\Exception('No columns to show');
        }

        return $this->columns;
    }

    /**
     * Sets the request object
     * @param $_REQUEST $request
     * @return Grid 
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * 
     * @param type $key
     * @return array
     */
    public function getRequest($key = false)
    {
        if ($key) {
            if (!isset($this->request[$key])) {
                return false;
            }

            return $this->request[$key];
        }

        return $this->request;
    }

    /**
     * 
     * @param int $value
     */
    public function setNumRecords($value)
    {
        $this->numRecords = (int) $value;
    }

    /**
     * 
     * @return int
     */
    public function getNumRecords()
    {
        return $this->numRecords;
    }
    
    /**
     * Returns HTML id of the grid
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets id of the grid
     * 
     * @param string $id
     * @return \Mgrid\Grid
     */
    public function setId($id)
    {
        $this->id = (string) $id;
        return $this;
    }
    
    /**
     * Checks if grid has filters
     * @return boolean
     */
    public function hasFilter()
    {
        return $this->hasFilter;
    }    

    /**
     * 
     * @param boolean $hasFilter
     * @return \Mgrid\Grid
     */
    public function setHasFilter($hasFilter)
    {
        $this->hasFilter = (bool) $hasFilter;

        return $this;
    }

    /**
     * Checks if grid has ordering
     * @return boolean
     */
    public function hasOrdering()
    {
        return $this->hasOrdering;
    }

    /**
     * Sets order for the grid
     * 
     * @param string $boolean String with an index column
     * @return Mgrid 
     */
    public function setOrdering($boolean)
    {
        $this->hasOrdering = (bool) $boolean;
        return $this;
    }
    
    /**
     * Checks if grid has pager
     * @return boolean 
     */
    public function hasPager()
    {
        //return ($this->hasPager && $this->pagerHandle->hasPager());
        return $this->hasPager;
    }

    /**
     * Sets pager for the grid
     * 
     * @param string $boolean String with an index column
     * @return Mgrid 
     */
    public function setHasPager($boolean)
    {
        $this->hasPager = (bool) $boolean;
        return $this;
    }

    /**
     * Checks if grid has export
     * @return boolean 
     */
    public function hasMassAction()
    {
        //return ($this->hasPager && $this->pagerHandle->hasPager());
        return $this->hasMassAction;
    }

    /**
     * Sets pager for the grid
     * 
     * @param string $boolean String with an index column
     * @return Mgrid 
     */
    public function setHasMassAction($boolean)
    {
        $this->hasMassAction = (bool) $boolean;
        return $this;
    }
    
    /**
     * Checks if grid has table Headers
     * @return boolean 
     */
    public function hasHeaders()
    {
        //return ($this->hasPager && $this->pagerHandle->hasPager());
        return $this->hasHeaders;
    }

    /**
     * Sets pager for the grid
     * 
     * @param string $boolean String with an index column
     * @return Mgrid 
     */
    public function setHeaders($boolean)
    {
        $this->hasHeaders = (bool) $boolean;
        return $this;
    }
}

