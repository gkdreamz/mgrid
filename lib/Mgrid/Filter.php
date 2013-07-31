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
        $this->filters[$key] = $value;

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
        $dateConverter = new \Mgrid\Filter\Converter\Date;
        
        $filters = $this->getFilters();

        // return raw rs
        if (count($filters) == 0) {
            return $resultSet;
        }

        $resultSetFiltered = array();

        //loop nos resultados
        foreach ($resultSet as $key => $row) {
            $bln_filter = true;

            foreach ($columns as $column) {
                //pego filtro
                $objFilter = $column->getFilter();

                //caso n tenha filtro para a coluna ignoro
                if (!$objFilter) {
                    continue;
                }

                $objRender = $objFilter->getRender();

                //pego informacoes 
                $range = $objRender->getRange();
                $conditions = $objRender->getCondition();

                // guardar valores padroes dos campos
                if ($range) {
                    //loop nos filtros
                    foreach ($filters as $keyCond => $fieldFilter) {
                        //nenhum elemento em array enviado
                        if (!is_array($fieldFilter))
                            continue;

                        // loop nos campos do tipo do filtro
                        foreach ($fieldFilter as $field => $value) {
                            foreach ($conditions['range'] as $typeCond => $condition)
                                // caso filtro com mesmo nome da coluna
                                if (($field == $column->getIndex()) && ($keyCond == $typeCond)) {
                                    $column->getFilter()->getRender()->setAttributeValue("value[{$typeCond}]", $value);
                                }
                        }
                    }
                } else {
                    //loop nos filtros
                    foreach ($filters as $filter => $value)
                        //caso filtro com mesmo nome da coluna
                        if ($filter == $column->getIndex()) {
                            //populo valor padrao
                            $column->getFilter()->getRender()->setAttributeValue('value', $value);
                        }
                }

                // caso ja setado como falso ignoro 
                if (!$bln_filter) {
                    continue;
                }

                // dentro de um range
                if ($range) {
                    //loop nos filtros
                    foreach ($filters as $keyCond => $fieldFilter) {
                        //nenhum elemento em array enviado
                        if (!is_array($fieldFilter)) {
                            continue;
                        }

                        // loop nos campos do tipo do filtro
                        foreach ($fieldFilter as $field => $value) {
                            // caso filtro n tenha valor nem comparo
                            if (empty($value)) {
                                continue;
                            }

                            foreach ($conditions['range'] as $typeCond => $condition) {
                                // caso ja setado como falso ignoro 
                                if (!$bln_filter) {
                                    continue;
                                }

                                // caso tipo de condicao diferente da condicao do range nao comparo
                                if ($keyCond != $typeCond) {
                                    continue;
                                }

                                // caso filtro com mesmo nome da coluna
                                if ($field == $column->getIndex()) {
                                    //valor da linha
                                    $rowValue = $row[$field];

                                    //tratamento quando sao tipo date
                                    if (is_object($rowValue)) {
                                        if (get_class($rowValue) == 'DateTime') {
                                            $rowValue = $rowValue->format('Ymd');
                                            $value = $dateConverter->fromBRtoNumber($value);
                                        }
                                    }

                                    $bln_filter = $this->gridFiltersConditions($condition, $rowValue, $value);
                                }
                            }
                        }
                    }
                    // valor especifico
                } else {
                    //loop nos filtros
                    foreach ($filters as $filter => $value) {
                        //elemento em array enviado
                        if (is_array($value))
                            continue;

                        //caso filtro com mesmo nome da coluna
                        if ($filter == $column->getIndex()) {

                            //valor da linha
                            $rowValue = $row[$filter];

                            //tratamento quando sao tipo date
                            if (is_object($rowValue)) {
                                if (get_class($rowValue) == 'DateTime') {
                                    $rowValue = $rowValue->format('Ymd');
                                    $value = $dateConverter->fromBRtoNumber($value);
                                }
                            }

                            foreach ($conditions['match'] as $condition) {
                                $bln_filter = $this->gridFiltersConditions($condition, $rowValue, $value);
                            }
                        }
                    }
                }
            }
            
            //posso adicionar
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
        
        $numberConverter = new \Mgrid\Filter\Converter\Number;
        
        
        switch ($condition) {
            case '>=':
                if ($numberConverter->toInt($fieldVal) >= $numberConverter->toInt($filterVal))
                    return true;
                break;
            case '<=':
                if ($numberConverter->toInt($fieldVal) <= $numberConverter->toInt($filterVal))
                    return true;
                break;
            case '>':
                if ($numberConverter->toInt($fieldVal) > $numberConverter->toInt($filterVal))
                    return true;
                break;
            case '<':
                if ($numberConverter->toInt($fieldVal) < $numberConverter->toInt($filterVal))
                    return true;
                break;
            case 'fulltext':
                //procuro pela palavra no em qlqr posicao da string
                if (stristr($fieldVal, $filterVal))
                    return true;
                break;
            case '=':
                //procuro pela palavra exatamente igual
                if ($fieldVal == $filterVal)
                    return true;
                break;
        }

        return false;
    }

}