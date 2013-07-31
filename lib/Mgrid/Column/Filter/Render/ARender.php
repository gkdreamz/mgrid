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

/**
 * A Render
 *
 * @since       0.0.1
 * @author      Renato Medina <medinadato@gmail.com>
 */

abstract class ARender
{

    /**
     * Defines if field should have hange or not
     * @var string
     */
    protected $range = false;

    /**
     * Filter that uses the render
     * 
     * @var Mgrid\Column\Filter
     */
    protected $filter = null;

    /**
     * Index from the column
     * @var type 
     */
    protected $fieldIndex;

    /**
     * Attributes
     */
    protected $attributes;

    /**
     * Condicoes da busca padrao para opções match e range 
     * @var array  match: fulltext, = | range: <>, >, <, >=, <= 
     */
    protected $condition = array(
        'match' => array('='), 
        'range' => array(
            'from' => '>=', 
            'to' => '<=',
            ),
        );

    /**
     * constructor may set options
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->setOptions($options);
        return $this;
    }

    /**
     * Set grid state from options array
     *
     * @param  array $options
     * @return Grid
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            
            //forbiden options
            if (in_array($method, array()))
                if (!is_object($value))
                    continue;

            if (!method_exists($this, $method)) {
                throw new \Mgrid\Exception("Unknown option {$method}");
            }
            
            $this->$method($value);
        }
        return $this;
    }

    /**
     * sets filter that uses the render
     * @param Mgrid\Column\Filter $filter
     * @return ARender 
     */
    public function setFilter(\Mgrid\Column\Filter $filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getFieldIndex()
    {
        return $this->getFilter()->getColumn()->getIndex();
    }

    /**
     * 
     * @return type
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * 
     * @return type
     */
    public function getRange()
    {
        return $this->range;
    }

    /**
     * 
     * @param type $range
     */
    public function setRange($range)
    {
        $this->range = $range;
    }

    /**
     * 
     * @return type
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * 
     * @param string $name
     * @param string $value
     * @return \Mgrid\Column\Filter\Render\ARender
     */
    public function setAttributeValue($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    /**
     * 
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        // default values
        $attributes['class'] = isset($attributes['class']) ? $attributes['class'] . ' mgrid-filter-field' : 'mgrid-filter-field';
        $attributes['value'] = isset($attributes['value']) ? $attributes['value'] : '' ;

        $this->attributes = $attributes;
    }

    /**
     * 
     * @return type
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * 
     * @param string $condition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }
}