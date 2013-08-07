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
 * Set the actions for the records
 * 
 * @since       0.0.1
 * @author      Renato Medina <medinadato@gmail.com>
 */
class Action
{

    /**
     * label of column
     * @var string
     */
    protected $label;

    /**
     * the target for the link
     * @var string
     */
    protected $href;

    /**
     * the params for the action
     * @var string
     */
    protected $params = array();

    /**
     * sets the condition the to action attend
     * @var string
     */
    protected $condition;

    /**
     * the css class use by link of the action
     * @var type 
     */
    protected $cssClass;

    /**
     * html title to the action
     * @var type 
     */
    protected $title;
    
    /**
     * @var type 
     */
    protected $target = '_parent';

    /**
     * contructor of the grid
     * @param array $options
     * @return Column 
     */
    public function __construct(array $options = array())
    {
        \Mgrid\Stdlib\Configurator::configure($this, $options);
        return $this;
    }

    /**
     * Gets de label
     * @return string
     */
    public function getLabel()
    {
        return (string) $this->label;
    }

    /**
     * Sets the label
     * 
     * @param string $label
     * @return Column 
     */
    public function setLabel($label)
    {
        $this->label = (string) $label;

        if (!$this->getTitle()) {
            $this->title = (string) $label;
        }

        return $this;
    }

    /**
     * sets the user defined url
     * @param string $url
     * @return Action 
     */
    public function setHref($href)
    {
        $this->href = (string) $href;
        return $this;
    }

    /**
     * returns the user defined url
     * @return string
     */
    public function getHref()
    {
        $url = $this->href;
        
        foreach($this->getParams() as $key => $value) {
            $url .= '&' . $key . '=' . $value;
        }
        
        return $url;
    }

    /**
     * returns the url paramns
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * sets the url params
     * @param type $params
     * @return Action 
     */
    public function setParams(array $params = array())
    {
        $this->params = $params;
        return $this;
    }

    /**
     * gets the condition thats the action attends
     * @return string
     */
    public function getCondition($row)
    {
        $cond = $this->condition;

        return ($cond != null) ? call_user_func($cond, $row) : true;
    }

    /**
     * sets the condition to the action attend
     * @param string $condition
     * @return Action 
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
        return $this;
    }

    /**
     * returns the css class of the action
     * @return type 
     */
    public function getCssClass()
    {
        return $this->cssClass;
    }

    /**
     * sets the css class of the action
     * @param string $cssClass 
     */
    public function setCssClass($cssClass)
    {
        $this->cssClass = (string) $cssClass;
    }

    /**
     * returns if the action attends to condition
     * @param array $row
     * @return bool
     */
    public function attendToRowCondition(array $row)
    {
        $this->getCondition($row);

        return true;
    }

    /**
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * 
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function setTarget($target)
    {
        $this->target = $target;
    }

}
