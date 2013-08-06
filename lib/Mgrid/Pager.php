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
 * Set number of records por pag
 * 
 * @since       0.0.2
 * @author      Renato Medina <medinadato@gmail.com>
 */
class Pager
{

    /**
     * @var array 
     */
    protected $request = array();

    /**
     * @var array 
     */
    protected $resultSet = array();
    
    /**
     * @var integer $maxPerPage Maximum number of itens per page
     */
    protected $maxPerPage = 25;
    
    /**
     * pager offset
     * @var int
     */
    protected $offset = 0;

    /**
     * @var int current position of the pagination
     */
    protected $curPos = 1;
    /**
     * @var integer $numRecords Number of results found
     */
    protected $numRecords;

    /**
     * @var integer $page Current page
     */
    protected $page;

    /**
     * @var integer $lastPage Last page (total of pages)
     */
    protected $lastPage;

    /**
     * pager maxset
     * @var int
     */
    protected $maxset;
    /**
     * Process the pagination
     * @return boolean
     */
    public function apply()
    {        
        if ($this->getNumRecords() == 0) {
            $this;
        }
        
        $this->adjustOffset()
                ->setResultSet(array_slice($this->getResultSet(), $this->getOffset(), $this->getMaxset()));

//$this->getSource()
//->setLimit($pager->getMaxPerPage())
//->setOffset($pager->getOffset());

        return $this;
    }

    /**
     * Returns request
     * @return int
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Sets the request
     * @return int
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }
    
    /**
     * Returns result set
     * @return int
     */
    public function getResultSet()
    {
        return $this->resultSet;
    }

    /**
     * Sets the result set
     * @return int
     */
    public function setResultSet($resultSet)
    {
        $this->resultSet = $resultSet;
        return $this;
    }
    
    /**
     * Returns the maximum number of itens per page
     *
     * @return int maximum number of itens per page
     */
    public function getMaxPerPage()
    {
        return $this->maxPerPage;
    }

    /**
     * Defines the maximum number of itens per page and automatically adjust 
     * offset and limits
     *
     * @param int $max maximum number of itens per page
     * @return \Mgrid\Pager
     */
    public function setMaxPerPage($max)
    {
        if (0 < (int) $max) {
            $this->maxPerPage = (int) $max;
        }
        
        $this->adjustOffset();

        return $this;
    }
    
    /**
     * returns the offset 
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }
    
    /**
     * returns the maxset 
     * @return int
     */
    public function getMaxset()
    {
        return ($this->getOffset() + $this->getMaxPerPage());
    }
    
    /**
     * Adjusts last page of Pager, offset and limit
     * @return void
     */
    protected function adjustOffset()
    {
        // Define new total of pages
        $this->setLastPage(max(1, ceil($this->getNumRecords() / $this->getMaxPerPage())));
        
        $offset = ($this->getCurPos() - 1) * $this->getMaxPerPage();
        $this->offset = $offset;
        
        return $this;
    }
    
    /**
     * Returns the number of results found
     *
     * @return int the number of results found
     */
    public function getNumRecords()
    {
        if(!$this->numRecords) {
            $this->numRecords = count($this->getResultSet());
        }
        
        return $this->numRecords;
    }
    
    /**
     * Returns the actual page
     * @return int
     */
    public function getCurPos()
    {
        if (isset($this->request['mgrid']['page'])) {
            $this->curPos = (int) $this->request['mgrid']['page'];
        }
        
        return $this->curPos;
    }

    /**
     * Sets the actual page
     * @return int
     */
    public function setCurPos($curPos)
    {
        $this->curPos = (int) $curPos;
        return $this;
    }

    /**
     * getFirstPage
     *
     * Returns the first page
     *
     * @return int        first page
     */
    public function getFirstPage()
    {
        return 1;
    }

    /**
     * Returns total of pages
     * @return int
     */
    public function getTotalPages()
    {
        return (int) ceil($this->getNumRecords() / $this->getMaxPerPage());
    }
    
    /**
     * getLastPage
     *
     * Returns the last page (total of pages)
     *
     * @return int        last page (total of pages)
     */
    public function getLastPage()
    {
        return $this->lastPage;
    }

    /**
     * setLastPage
     *
     * Defines the last page (total of pages)
     *
     * @param $page       last page (total of pages)
     * @return void
     */
    protected function setLastPage($page)
    {
        $this->lastPage = $page;

        if ($this->getCurPos() > $page) {
            $this->setCurPos($page);
        }
    }

    /**
     * getNextPage
     *
     * Returns the next page
     *
     * @return int next page
     */
    public function getNextPage()
    {
        return (int) min($this->getCurPos() + 1, $this->getLastPage());
    }

    /**
     * getPreviousPage
     *
     * Returns the previous page
     *
     * @return int        previous page
     */
    public function getPreviousPage()
    {
        return (int) max($this->getCurPos() - 1, $this->getFirstPage());
    }

    /**
     * Return the first indice number for the current page
     *
     * @return int First indice number
     */
    public function getFirstIndice()
    {
        return ($this->getCurPos() - 1) * $this->getMaxPerPage() + 1;
    }

    /**
     * Return the last indice number for the current page
     *
     * @return int Last indice number
     */
    public function getLastIndice()
    {
        return min($this->getNumResults(), ($this->getPage() * $this->getMaxPerPage()));
    }

    /**
     * 
     * @param array $params
     * @return string
     */
    public function getUrl(array $params = array())
    {
        return $_SERVER['SCRIPT_NAME'];
    }
    
    /**
     * 
     * @return string
     */
    public function getCurUrl($index)
    {
        return $this->getUrl() . '?mgrid[page]=' . $index;
    }
    
    /**
     * 
     * @return string
     */
    public function getPreviousUrl()
    {
        return $this->getUrl() . '?mgrid[page]=' . $this->getPreviousPage();
    }
    
    /**
     * 
     * @return string
     */
    public function getNextUrl()
    {
        return $this->getUrl() . '?mgrid[page]=' . $this->getNextPage();
    }
}
