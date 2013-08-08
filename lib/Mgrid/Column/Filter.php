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

namespace Mgrid\Column;

/**
 * A Render
 *
 * @since       0.0.1
 * @author      Renato Medina <medinadato@gmail.com>
 */

class Filter
{
    
    /**
     * render used by filter
     * @var array
     */
    protected $render = null;
    
    /**
     *
     * @var object \Mgrid\Column
     */
    protected $column;

    /**
     * Constructor of the class
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

	    if (method_exists($this, $method))
                // Setter exists; use it
		$this->$method($value);
	    else
		throw new Grid\Exception("Unknown option {$method}");
	}
	return $this;
    }
    
    /**
     *
     * @param \Column $column
     * @return Mgrid\Column\Filter 
     */
    public function setColumn(\Mgrid\Column $column)
    {
        $this->column = $column;
        return $this;
    }
    
    /**
     *
     * @return type 
     */
    public function getColumn()
    {
        return $this->column;
    }
    
    /**
     * Set the render
     *
     * $render may be either an array column options, or an object of type
     * Mgrid\Column\Filter\Render. 
     *
     * @param  array|Mgrid\Column\Filter\Render\ARender $render
     * @return Mgrid\Column\Filter
     * @throws \Exception 
     */
    public function setRender($render)
    {
	if (is_array($render)) {
	    if (isset($render['type'])) {
		$type = ucfirst($render['type']);
		$className = "\Mgrid\Column\Filter\Render\\{$type}";
                
                //verifico alguns atriputos manualmente
                unset($render['type']);
                if(!isset($render['attributes']))
                     $render['attributes'] = array();
                
		$render = new $className($render);
	    } else {
		throw new \Exception("Renders specified by array must have a 'type' index");
	    }
	} elseif (is_object($render) && $render instanceof Mgrid\Column\Filter\Render\IRender) {
	    $render = $render;
	} elseif (is_string($render)) {
	    $render = ucfirst($render);
	    $className = "\Mgrid\Column\Filter\Render\\{$render}";
	    $render = new $className;
	} else {
	    throw new \Exception('Invalid render');
	}

	$render->setFilter($this);
	$this->render = $render;
	return $this;
    }
    
    /**
     *
     * @return object 
     */
    public function getRender()
    {
	if (null === $this->render) {
	    $render = new Column\Filter\Render\Text;
	    $render->setFilter($this);
	} else {
	    $render = $this->render;
	}

	return $render;
    }
    
    /**
     *
     * @return type 
     */
    public function render()
    {
        
        return $this->getRender()->render();
    }

}
