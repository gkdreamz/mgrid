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

namespace Mgrid\Column\Render;

/**
 * Abstract class for Filter renders
 *
 * 
 * @since       0.0.1
 * @author      Renato Medina <medinadato@gmail.com>
 */

abstract class ARender implements IRender
{

    /**
     * row used by render
     * @var array
     */
    protected $row = array();
    
    /**
     * Column that uses the render
     * @var Mgrid\Column
     */
    protected $column = null;
    
    /**
     * render options
     */
    protected $options;

    /**
     * constructor may set options
     * @param array $options 
     */
    public function __construct(array $options = array())
    {
	if (isset($options))
	    $this->options = $options;
    }

    /**
     * sets a row to be parsed by render
     * @param array $row 
     * @return ARender 
     */
    public function setRow(array $row)
    {
	$this->row = $row;
	return $this;
    }

    /**
     * returns the row
     * @return array
     */
    public function getRow()
    {
	return $this->row;
    }

    /**
     * returns column that uses the render
     */
    public function getColumn()
    {
	return $this->column;
    }

    /**
     * sets column that uses the render
     * @param Mgrid\Column $column
     * @return ARender 
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
    public function getView()
    {
	//get view
	$viewRenderer = \Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer');
	return $viewRenderer->view;        
    }
}
