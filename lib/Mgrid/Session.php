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
 * Controls the grid session
 *
 * @since       0.0.2
 * @author      Renato Medina <medinadato@gmail.com>
 */
class Session
{

    /**
     *
     * @var $_SESSION 
     */
    private $session;

    /**
     * 
     */
    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $_SESSION['mdn_mgrid'] = NULL;

        $this->session = $_SESSION['mdn_mgrid'];
    }

    /**
     * 
     * @param string $key
     * @return boolean
     */
    public function hasParam($key)
    {
        if (isset($this->session[$key])) {
            return true;
        }

        return false;
    }

    /**
     * 
     * @param string $key
     * @param mixed $value
     * 
     * @return \Mgrid\Session
     */
    public function setData($key, $value)
    {
        $this->session[$key] = $value;

        return $this;
    }

    /**
     * 
     * @param string $key
     * @return mixed
     */
    public function getData($key = false)
    {
        if (!$key) {
            return $this->session;
        }

        if (!isset($this->session[$key])) {
            return false;
        }

        return $this->session[$key];
    }

    /**
     * 
     * @param string $key
     * @return boolean
     */
    public function unsetData($key)
    {
        if (!isset($this->session[$key])) {
            return false;
        }

        unset($this->session[$key]);

        return true;
    }

}