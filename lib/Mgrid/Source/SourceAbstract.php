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

namespace Mgrid\Source;

/**
 * Basic methods for the source object
 *
 * @author Renato Medina <medinadato@gmail.com>
 */

abstract class SourceAbstract
{

    /**
     * grid used by source
     * @var Grid
     */
    protected $grid = null;
    /**
     * pager used by source
     * @var type 
     */
    protected $pager = null;
    /**
     * order config of the source
     * @var array
     */
    protected $order = array();

    /**
     * Sets the order config
     * @param array $order
     * @return ASource 
     */
    public function setOrder(array $order)
    {
	$this->order = $order;
	return $this;
    }

    /**
     * sets the limit
     * @param type $limit
     * @return ASource 
     */
    public function setLimit($limit)
    {
	$this->limit = (int) $limit;
	return $this;
    }

    /**
     * sets the offset
     * @param type $offset
     * @return ASource 
     */
    public function setOffset($offset)
    {
	$this->offset = (int) $offset;
	return $this;
    }

}