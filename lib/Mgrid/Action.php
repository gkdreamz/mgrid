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
     * the name of the action used on the url
     * @var string
     */
    protected $actionName;
    /**
     * the name of the controller used on the url
     * @var string
     */
    protected $controllerName;
    /**
     * the actions params url by url
     * @var string
     */
    protected $params;
    /**
     * the static url string defined by user
     * @var string
     */
    protected $userDefinedUrl;
    /**
     * sets the condition the to action attend
     * @var string
     */
    protected $condition;
    /**
     * the row index that contain the PK of the record
     * @var mixed
     */
    protected $pkIndex;
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
     * @param string $label
     * @return Column 
     */
    public function setLabel($label)
    {
	$this->label = (string) $label;
        
        if(!$this->getTitle())
            $this->title = (string) $label;
        
	return $this;
    }

    /**
     * sets the user defined url
     * @param string $url
     * @return Action 
     */
    public function setUserDefinedUrl($url)
    {
	$this->userDefinedUrl = (string) $url;
	return $this;
    }

    /**
     * returns the user defined url
     * @return string
     */
    public function getUserDefinedUrl()
    {
	return null;
    }

    /**
     * sets the action name
     * @param string $actionName
     * @return Action 
     */
    public function setActionName($actionName)
    {
	$this->actionName = (string) $actionName;
	return $this;
    }

    /**
     * returns the action name
     * @return string
     */
    public function getActionName()
    {
	return $this->actionName;
    }

    /**
     * sets the controller name
     * @param type $controllerName
     * @return Action 
     */
    public function setControllerName($controllerName)
    {
	$this->controllerName = (string) $controllerName;
	return $this;
    }

    /**
     * returns the controller name
     * @return type 
     */
    public function getControllerName()
    {
	return $this->controllerName;
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
    public function setParams(array $params)
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
     * returns the PK Index
     * @return mixed
     */
    public function getPkIndex()
    {
	return $this->pkIndex;
    }

    /**
     * the PK index of the row
     * @param mixed $pkIndex
     * @return Action 
     */
    public function setPkIndex($pkIndex)
    {
	$this->pkIndex = $pkIndex;
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
     * returns the url string based on a row
     * 
     * @param array $row
     * @return string
     */
    public function getUrl(array $row)
    {
	$params = array();
        $url = '';

	if (null !== $this->getUserDefinedUrl()) {
	    return $this->getUserDefinedUrl();
	} 
	    
        if (null !== $this->getActionName()) {
            $params['action'] = $this->getActionName();
        }

        if (null !== $this->getControllerName()) {
            $params['controller'] = $this->getControllerName();
        }
        if (null !== $this->getPkIndex()) {
            $params[$this->getPkIndex()] = $row[$this->getPkIndex()];
        }
        if (null !== $this->getParams()) {
            $params = array_merge($params, $this->getParams());
        }
        
	return $url;
    }

    public function getTitle()
    {
	return $this->title;
    }

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
