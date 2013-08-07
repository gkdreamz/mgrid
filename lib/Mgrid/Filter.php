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
 * Handle the filters
 *
 * 
 * @since       0.0.2
 * @author      Renato Medina <medinadato@gmail.com>
 */
class Filter
{

    /**
     *
     * @var type 
     */
    private $filters = array();

    /**
     * 
     * @param mixed $key
     * @param mixed $value
     */
    public function addFilter($key, $value)
    {
        $myfilter = function($key, $value) use (&$myfilter) {
                    if (is_array($key)) {
                        return (count($key) > 0) ? array($key[0] => $myfilter(array_slice($key, 1), $value)) : $value;
                    }
                    return array($key => $value);
                };

        $this->filters = array_merge($this->filters, $myfilter($key, $value));

        return $this;
    }

    /**
     * 
     * @return type
     */
    protected function getFilters()
    {
        return $this->filters;
    }

    /**
     * Applies required filters
     * 
     * @param array $columns
     * @param array $resultSet
     * @return array
     */
    public function apply(array $columns, array $resultSet)
    {
        $config = \Mgrid\Config::getConfig('render');

        $filters = $this->getFilters();

        // return raw rs
        if (count($filters) == 0) {
            return $resultSet;
        }

        $resultSetFiltered = array();

        foreach ($resultSet as $key => $row) {
            $bln_filter = true;

            foreach ($columns as $column) {

                $objFilter = $column->getFilter();

                // no filter to the column
                if (!$objFilter) {
                    continue 1;
                }

                // render
                $objRender = $objFilter->getRender();
                $range = $objRender->getRange();
                $conditions = $objRender->getCondition();

                // save default values into the fields
                if ($range) {

                    // filters check
                    foreach ($filters as $keyCond => $fieldFilter) {

                        if (!is_array($fieldFilter)) {
                            continue 1;
                        }

                        // loop fields with filter
                        foreach ($fieldFilter as $field => $value) {

                            foreach ($conditions['range'] as $typeCond => $condition)

                            // filter with same name as column
                                if (($field == $column->getIndex()) && ($keyCond == $typeCond)) {
                                    $column->getFilter()->getRender()->setAttributeValue("value[{$typeCond}]", $value);
                                }
                        }
                    }
                } else {
                    
                    foreach ($filters as $filter => $value) {

                        // matching filter e column
                        if ($filter != $column->getIndex()) {
                            continue 1;
                        }

                        // set default value
                        $column->getFilter()
                                ->getRender()
                                ->setAttributeValue('value', $value);
                    }
                }

                
                if (!$bln_filter) {
                    continue 1;
                }

                // It's not nice, I know!! Too tired to work on that right now =)
                if ($range) {

                    foreach ($filters as $keyCond => $fieldFilter) {

                        if (!is_array($fieldFilter)) {
                            continue;
                        }

                        foreach ($fieldFilter as $field => $value) {

                            if (empty($value)) {
                                continue 1;
                            }

                            foreach ($conditions['range'] as $typeCond => $condition) {

                                if (!$bln_filter) {
                                    continue 1;
                                }

                                // case type condition and range different
                                if ($keyCond != $typeCond) {
                                    continue 1;
                                }

                                // matching field and filter
                                if ($field != $column->getIndex()) {
                                    continue 1;
                                }

                                $rowValue = $row[$field];
                                
                                // datetime case
                                if ($column->getRender($row) instanceof \Mgrid\Column\Render\Date) {
                                    $dateTime = \DateTime::createFromFormat($config['date.format.to'], $value);
                                    $value = $dateTime->format($config['date.format.from']);
                                }
                                
                                // check if has filter
                                $bln_filter = $this->gridFiltersConditions($condition, $rowValue, $value);
                            }
                        }
                    }
                } else {
                    
                    foreach ($filters as $filter => $value) {
                        
                        if (is_array($value)) {
                            continue 1;
                        }

                        // matching field and filter
                        if ($filter == $column->getIndex()) {

                            $rowValue = $row[$filter];
                            
                            // datetime case
                            if ($column->getRender($row) instanceof \Mgrid\Column\Render\Date) {
                                $dateTime = \DateTime::createFromFormat($config['date.format.to'], $value);
                                $value = $dateTime->format($config['date.format.from']);
                            }

                            foreach ($conditions['match'] as $condition) {
                                $bln_filter = $this->gridFiltersConditions($condition, $rowValue, $value);
                            }
                        }
                    }
                }
            }

            // add to the grid
            if ($bln_filter) {
                array_push($resultSetFiltered, $row);
            }
        }

        return $resultSetFiltered;
    }

    /**
     * Checa as condicoes dos valores do campo com a da culuna baseados na condicao
     * 
     * @param string $condition
     * @param string $fieldVal
     * @param string $filterVal
     * @return boolean 
     */
    private function gridFiltersConditions($condition, $fieldVal, $filterVal)
    {
        switch ($condition) {
            case '>=':
                if ($fieldVal >= $filterVal)
                    return true;
                break;
            case '<=':
                if ($fieldVal <= $filterVal)
                    return true;
                break;
            case '>':
                if ($fieldVal > $filterVal)
                    return true;
                break;
            case '<':
                if ($fieldVal < $filterVal)
                    return true;
                break;
            case 'fulltext':
                if (stristr($fieldVal, $filterVal))
                    return true;
                break;
            case '=':
                if ($fieldVal == $filterVal)
                    return true;
                break;
        }

        return false;
    }

}