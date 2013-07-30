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
class Order
{
    /**
     *
     * @var $_SESSION 
     */
    private $sessionHandle;
    
    /**
     * 
     */
    public function __construct()
    {
        // set session handle
        $this->sessionHandle = new \Mgrid\Session;
    }
    
    /**
     * Applies required filters
     * 
     * @param array $columns
     * @param array $resultSet
     * @param array $request
     * @return array
     */
    public function apply(array $columns, array $resultSet, array $request)
    {
        // process individual columns
        $sorting = $this->processColumnOrder($columns, $request);

        //caso exista coluna definida
        if ($sorting['colOrder']) {
            // set sorting
            $resultSet = $this->orderBy($resultSet, $sorting['colOrder'], $sorting['dirOrder']);
            // save on the session            
            $this->sessionHandle->setData('ordering', array(
                'colOrder' => $sorting['colOrder'],
                'dirOrder' => $sorting['dirOrder'],
            ));
        }

        return $resultSet;
    }


    /**
     * 
     * @param array $columns Columns
     * @param array $params Request parameters
     * @return \Mgrid\Order
     */
    private function processColumnOrder(array $columns, array $params)
    {
        // checo se tenho ordenacao
//        if (!$this->hasOrdering()) {
//            $blnOrdering = false;
//            //caso alguma coluna definida seto grid com ordenacao
//            foreach ($columns as $column) {
//                if ($column->hasOrdering() === true)
//                    $blnOrdering = true;
//                else
//                    $column->setHasOrdering(false);
//            }
//
//            if (!$blnOrdering)
//                return $this;
//
//            $this->setHasOrdering($blnOrdering);
//        }

        // remove sorting out of the session
        if (isset($params['mgrid']['removeOrder'])) {
            $this->sessionHandle->unsetData('ordering');
        }
        // order
        $colOrder = false;
        $dirOrder = false;
        
        // checo coluna selecionada via session ou parametros
        if($this->sessionHandle->hasParam('ordering')) {
            $sorting = $this->sessionHandle->getData('ordering');
            $colOrder = $sorting['colOrder'];
            $dirOrder = $sorting['dirOrder'];
        }

        $colOrder = (isset($params['mgrid']['colOrder'])) ? $params['mgrid']['colOrder'] : $colOrder;
        $dirOrder = (isset($params['mgrid']['dirOrder'])) ? $params['mgrid']['dirOrder'] : $dirOrder;

        //checo colunas com ordering
        foreach ($columns as $column) {
            if ($colOrder == $column->getIndex()) {
                //nova ordenacao para a coluna
                $column->setDirOrder((($dirOrder == 'ASC') ? 'DESC' : 'ASC'));
            }
        }

        //defino colunas nulas com ordenacao
        foreach ($columns as $column) {
            if ($column->hasOrdering() === null)
                $column->setOrdering(true);
        }
        
        return array(
            'colOrder' => $colOrder, 
            'dirOrder' => $dirOrder,
            );
    }
    
    /**
     * Sort an array based in a Assoc index
     * 
     * @param array $data Array to be sorted
     * @param string $field Field to be used to sort
     * @param string $direction Direction to be done ASC | DESC
     * @return array 
     */
    private function orderBy($data, $field, $direction = 'ASC')
    {
        //verifico se tenho datas para converter para strings
        $code = "if (is_object(\$a['$field']) && (get_class(\$a['$field']) == 'DateTime')) \$a['$field'] = \$a['$field']->format('Ymd'); ";
        $code .= "if (is_object(\$b['$field']) && (get_class(\$b['$field']) == 'DateTime')) \$b['$field'] = \$b['$field']->format('Ymd'); ";
        //ordenacao
        $code .= "return strnatcmp(\$a['$field'], \$b['$field']);";
        usort($data, create_function('$a,$b', $code));

        return ($direction == 'DESC') ? array_reverse($data) : $data;
    }
}