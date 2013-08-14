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
    protected $hasFilter;

    /**
     * @var boolean if the grid should order results, by default yes
     */
    protected $hasOrder;

    /**
     * @var boolean if the grid should have pager
     */
    protected $hasPager;

    /**
     * @var boolean if the grid should have table headers
     */
    protected $hasHeader;
    
    /**
     * @var boolean defines if should or not show actions for columns
     */
    protected $hasActions = false;

    /**
     * @var boolean if the grid should have export
     */
    protected $hasExport;

    /**
     * @var boolean if the grid should have mass actions
     */
    protected $hasMassAction;

    /**
     * @var array  
     */
    protected $resultSet;

    /**
     * Number of result sets found
     * @var int 
     */
    protected $numberFoundRecords;

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
     * grid config settings
     */
    protected $config;

    /**
     * Load the basic configuration
     */
    public function __construct()
    {
        // set session handle
        $this->sessionHandle = new \Mgrid\Session;

        // set filter handle
        $this->filterHandle = new \Mgrid\Filter;

        // set ordering handle
        $this->orderHandle = new \Mgrid\Order;

        // set pager handle
        $this->pagerHandle = new \Mgrid\Pager;
        
        // load default settings
        $this->loadSettings();

        // set request
        $this->setRequest($_REQUEST);
    }
    
    /**
     * Load default settings 
     */
    private function loadSettings()
    {
        // general configs
        $this->config = \Mgrid\Config::getConfig();
        
        // twig
        $template_path = __DIR__ . '/templates/' . $this->config['template']['skin'] . '/';
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem($template_path));
        
        // grid
        \Mgrid\Stdlib\Configurator::configure($this, \Mgrid\Config::getConfig('grid'));
        
        // pagination
        if(!isset($this->config['pager']['recordsPerPage'])) {
            throw new \Mgrid\Exception('There is no settings for number of records per page');
        }
        
        $this->pagerHandle->setMaxPerPage($this->config['pager']['recordsPerPage']);
    }

    /**
     * Returns the HTML output
     */
    public function render()
    {   
        // build the grid
        $this->build();
        
        // render it
        return $this->twig->render('grid.twig', array(
                    'grid' => $this,
                    'pager' => $this->pagerHandle,
                    'config' => $this->config,
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
        // load grid settings
        $this->init();
        
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
        $hasFilter = false;
        
        foreach ($this->getColumns() as $column) {
            // case forced not to show
            if($this->hasFilter() === false) {
                break 1;
            }
            
            if ($column->hasFilter()) {
                $hasFilter = true;
            }
        }
        
        $this->setFilter($hasFilter);

        // sent parameters
        $params = $this->getRequest();

        // remove filters
        if (isset($params['mgrid']['removeFilter'])) {
            $this->sessionHandle->unsetData('filter');
            return $this;
        }

        // add valid filters
        if (isset($params['mgrid']['addFilter'])) {
            $this->sessionHandle->setData('filter', $params['mgrid']['filter']);
        }

        // there is parametes into the session
        if ($this->sessionHandle->hasParam('filter')) {
            if (!isset($params['mgrid']['filter'])) {
                $params['mgrid']['filter'] = array();
            }
            $params['mgrid']['filter'] = array_merge($params['mgrid']['filter'], $this->sessionHandle->getData('filter'));
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

                    $this->filterHandle->addFilter(array($key, $subKey), $subVal);
                }
            } elseif (!empty($value)) {
                $this->filterHandle->addFilter($key, $value);
            }
        }

        // add filters to session
        $this->sessionHandle->setData('filter', $params['mgrid']['filter']);

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
        if (!$this->hasOrder()) {
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
        $resultSet = $this->pagerHandle->setResultSet($this->getResultSet())
                ->setRequest($this->getRequest())
                ->apply()
                ->getResultSet();
        
        $this->setResultSet($resultSet);

        return $this;
    }
    
    /**
     * 
     * @param array $custom_params Params to be added into the url
     * @return string
     */
    public function getAppUrl(array $custom_params = array())
    {
        // user data
        $host_url = $_SERVER['REQUEST_URI'];
        $host_url_params = array();
        // slice url
        $host_url_pieces = parse_url($host_url);

        // create host/path
        $host = isset($host_url_pieces['host']) ? $host_url_pieces['host'] : '';
        $path = isset($host_url_pieces['path']) ? $host_url_pieces['path'] : '';

        // create array out of url params
        if(isset($host_url_pieces['query'])) {
            parse_str($host_url_pieces['query'], $host_url_params);
        }
        
        // clean previous mgrid params
        unset($host_url_params['mgrid']);

        // all needed params
        $all_params = array_merge($custom_params, $host_url_params);
        
        // set new url
        $new_url = $host . $path . '?' . http_build_query($all_params);
        
        return $new_url;       
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

            if (null === $options['index']) {
                throw new \Mgrid\Exception('Columns specified by array must have an accompanying index');
            }

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
        $this->setActions(true);
        
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
     * 
     * @param array $row
     * @return array
     */
    public function getActionsByRow(array $row)
    {
        $tmpActions = array();
        $actions = $this->getActions();
        
        foreach ($actions as $action) {
            if ($action->attendToRowCondition($row)) {
                $tmpActions[] = $action;
            }
        }
        return $tmpActions;
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
        $this->setNumberFoundRecords(count($resultSet));

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
    public function setNumberFoundRecords($value)
    {
        $this->numberFoundRecords = (int) $value;
    }

    /**
     * 
     * @return int
     */
    public function getNumberFoundRecords()
    {
        return $this->numberFoundRecords;
    }
    
    /**
     * 
     * @param int $value
     */
    public function setRecordsPerPage($value)
    {
        $this->pagerHandle->setMaxPerPage((int) $value);
        
        return $this;
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
    public function setFilter($hasFilter)
    {
        $this->hasFilter = (bool) $hasFilter;

        return $this;
    }

    /**
     * Checks if grid has ordering
     * @return boolean
     */
    public function hasOrder()
    {
        return $this->hasOrder;
    }

    /**
     * Sets order for the grid
     * 
     * @param string $boolean String with an index column
     * @return Mgrid 
     */
    public function setOrder($boolean)
    {
        $this->hasOrder = (bool) $boolean;
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
    public function setPager($boolean)
    {
        $this->hasPager = (bool) $boolean;
        return $this;
    }
    
    /**
     * Checks if grid has actions
     * @return boolean 
     */
    public function hasActions()
    {
        return $this->hasActions;
    }

    /**
     * Sets pager for the grid
     * 
     * @param string $boolean set actions for columns
     * @return Mgrid 
     */
    public function setActions($boolean)
    {
        $this->hasActions = (bool) $boolean;
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
     * Checks if grid has table Header
     * @return boolean 
     */
    public function hasHeader()
    {
        //return ($this->hasPager && $this->pagerHandle->hasPager());
        return $this->hasHeader;
    }

    /**
     * Sets pager for the grid
     * 
     * @param string $boolean String with an index column
     * @return Mgrid 
     */
    public function setHeader($boolean)
    {
        $this->hasHeader = (bool) $boolean;
        return $this;
    }

}

