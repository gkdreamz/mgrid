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
 * Text filter type
 *
 * @since       0.0.1
 * @author      Renato Medina <medinadato@gmail.com>
 */

class Text extends Render\ARender implements Render\IRender
{
    
    /**
     *
     * @return string
     */
    public function render()
    {
        $attributes = $this->getAttributes();

        // set name
        $attributes['name'] = $attributes['id'] = 'mgrid[filter][' . $this->getFieldIndex() . ']';

        $html = '<input type="text" ';
        
        foreach($attributes as $name => $value) {
            if(is_array($value)) {
                $value = implode(' ', $value);
            }
            
            $html .= ' ' . $name . '="' . $value . '" ';
        }
        
        $html .= ' />';
        
        echo $html;
    }

}