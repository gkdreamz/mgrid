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

namespace Mgrid\Filter\Converter;

/**
 * Handle the numeric filters
 * 
 * @since       0.0.2
 * @author      Renato Medina <medinadato@gmail.com>
 */

class Number
{

    /**
     *
     * @param float $val
     * @return integer 
     */
    public function toInt($float)
    {
        return (int) preg_replace('[\D]', '', $float);
    }

    /**
     * Returns any value in decimal e.g. 824169.02
     * @param string $value
     * @param type $precision
     * @return decimal  
     */
    public function toDecimal($value, $precision = 2)
    {

        $factor = (strpos($value, '-')) ? - 1 : 1;

        // caso contenha uma virgula e um ou mais pontos
        if (substr_count($value, ',') == 1 && substr_count($value, '.') != 0) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        }
        // caso uma virgula e nenhum ponto
        if (substr_count($value, ',') == 1 && substr_count($value, '.') == 0) {
            $value = str_replace(',', '.', $value);
        }

        return number_format(($value * $factor), $precision, '.', '');
    }

    /**
     * Returns any value in money e.g. 82.169,02
     * @param string $value
     * @param type $precision
     * @return money
     */
    public function toMoney($value, $precision = 2)
    {
        return number_format($value, $precision, ',', '.');
    }

}
