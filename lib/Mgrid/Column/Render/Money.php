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

use Mgrid\Column\Render;

/**
 * Handle Columns
 * 
 * @since       0.0.1
 * @author      Renato Medina <medinadato@gmail.com>
 */

class Money extends Render\ARender implements Render\IRender
{

    /**
     *
     * @return string
     */
    public function render()
    {
        $row = $this->getRow();
        $index = $this->getColumn()->getIndex();
        
        $config = \Mgrid\Config::getConfig('render');

        // output
        $prefix = $config['currency.output.prefix'];
        $suffix = $config['currency.output.suffix'];
        $decimals = $config['currency.output.decimals'];
        $dec_point = $config['currency.output.dec_point'];
        $thousands_sep = $config['currency.output.thousands_sep'];
        
        $output = $prefix . number_format($row[$index], $decimals, $dec_point, $thousands_sep) . $suffix;

        return $output;
    }

}