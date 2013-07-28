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
     * @var Pager pager used by grid
     */
    protected $pager;
    /**
     *
     * @var type 
     */
    protected $hasPager = false;

    /**
     * @var boolean if the grid should paginate results
     */
    protected $showPager = true;

    /**
     * @var int number of the actual page
     */
    protected $page;
    
    /**
     * returns the pager used by grid
     * @return Mgrid\Pager; 
     */
    public function getPager()
    {
        return $this->pager;
    }

    /**
     * sets the pager
     * @param Mgrid\Pager $pager
     * @return Grid 
     */
    public function setPager(\Mgrid\Pager\Builder $pager)
    {
        $this->pager = $pager;
        $this->hasPager = true;
        return $this;
    }

    /**
     * Returns the actual page
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Sets the actual page
     * @return int
     */
    public function setPage($page)
    {
        $this->page = (int) $page;
        return $this;
    }
    
    /**
     * Process the pager 
     * 
     * @param array $result_set
     * @param array $request
     * @return \Mgrid\Pager
     */
    public function apply(array $result_set, array $request = array())
    {

        // if page don't exists
        if (null == $this->getPage()) {
            $num_page = (isset($request['page'])) ? (int) $request['page'] : 0;
            $this->setPage($num_page);
        }

        if (null === $this->getPager()) {
            //get default pager
            $pager = new \Mgrid\Pager\Builder( count($result_set), $this->getPage());
            $this->setPager($pager);
        } else {
            //get user defined pager
            $pager = $this->getPager();
        }

        $resultFiltered = array();

        if (count($result_set)) {
            foreach ($result_set as $key => $row) {
                if (($key >= $pager->getOffset()) && ($key < $pager->getMaxset())) {
                    array_push($resultFiltered, $row);
                }
            }
        }

        //seto resultado filtrado pela ordenacao para a grid 
        $this->result = $resultFiltered;

        //$this->getSource()
        //->setLimit($pager->getMaxPerPage())
        //->setOffset($pager->getOffset());

        $this->hasPager = true;
        
        return $this;
    }
}
