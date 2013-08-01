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

namespace Mgrid\Column\Filter\Render;

use Mgrid\Column\Filter\Render;

/**
 * Date filter type
 *
 * @since       0.0.1
 * @author      Renato Medina <medinadato@gmail.com>
 */
class Date extends Render\ARender implements Render\IRender
{

    public $renderChild = false;

    /**
     *
     * @return array 
     */
    public function getChilds()
    {
        return array('from' => $this->view->translate('Between'), 'to' => $this->view->translate('and'));
    }

    /**
     * Retuns current conditions
     *
     * @return array
     */
    public function getConditions()
    {
        return array(
            'match' => array('='),
            'range' => array('from' => '>=', 'to' => '<='),
        );
    }

    /**
     *
     * @return string
     */
    public function render()
    {
        $attributes = $this->getAttributes();
        
        // set name
        $attributes['name'] = $attributes['id'] = 'mgrid[filter][from][' . $this->getFieldIndex() . ']';
        // attributes for number
        $attributes['style'] = 'width: 100px;';
        $attributes['alt'] = 'date';
        $attributes['class'] .= ' date';
        $attributes['value'] = isset($attributes['value[from]']) ? $attributes['value[from]'] : '';

        $input1 = '<input type="text" ';
        $input1 .= $this->generateHtmlOfAttributes($input1, $attributes);
        $input1 .= ' />';
        
        $html = $input1;
        
        if ($this->getRange()) {
            $span = '<span> to </span>';
            
            $attributes['name'] = $attributes['id'] = 'mgrid[filter][to][' . $this->getFieldIndex() . ']';
            $attributes['value'] = isset($attributes['value[to]']) ? $attributes['value[to]'] : '';
            
            
            $input2 = '<input type="text" ';
            $input2 .= $this->generateHtmlOfAttributes($input2, $attributes);
            $input2 .= ' />';
            
            $html .= $span . $input2;
        }
        
        return $html;
    }
}