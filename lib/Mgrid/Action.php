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
     * the keys from the source for the action
     * @var string
     */
    protected $keys = array();

    /**
     * the user params for the action
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
     * returns the user defined href
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }
    
    /**
     * Returns the treated url based in the user prefs
     * 
     * @param array $row
     * @return string
     */
    public function getUrl(array $row = array())
    {
        // user data
        $user_url = $this->getHref();
        $user_defined_params = $this->getParams();
        $user_keys = $this->getKeys();
        $user_url_params = array();
        // slice url
        $user_url_pieces = parse_url($user_url);

        // create host/path
        $host = isset($user_url_pieces['host']) ? $user_url_pieces['host'] : '';
        $path = isset($user_url_pieces['path']) ? $user_url_pieces['path'] : '';

        // create array out of url params
        if(isset($user_url_pieces['query'])) {
            parse_str($user_url_pieces['query'], $user_url_params);
        }
               
        foreach($user_keys as $key => $value) {
            if(!isset($row[$value])) {
                continue 1;
            }
            
            if(is_numeric($key)) {
                $key = $value;
            }
            
            $user_defined_params[$key] = $row[$value];
        }

        // all needed params
        $all_params = array_merge($user_defined_params, $user_url_params);
        
        // set new url
        $new_url = $host . $path . '?' . http_build_query($all_params);

        return $new_url;
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
     * returns the url paramns
     * @return array
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * sets the url keys
     * @param type $keys
     * @return Action 
     */
    public function setKeys(array $keys = array())
    {
        $this->keys = $keys;
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
