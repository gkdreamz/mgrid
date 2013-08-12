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

namespace Demo\Grid;


use  Mgrid\Grid;

/**
 * Example of using all the components of the Mgrid Class
 *
 * @since       0.0.2
 * @author      Renato Medina <medinadato@gmail.com>
 */
class Simple02 extends Grid
{
    /**
     * It sets the grid properties
     */
    public function init()
    {
        // load the grid
        $this->setId('demo-full-grid')
                ->addColumn(array(
                    'label' => 'Cod.',
                    'index' => 'id',
                ))
                ->addColumn(array(
                    'label' => 'Company',
                    'index' => 'companyName',
                ))
                ->addColumn(array(
                    'label' => 'Role',
                    'index' => 'roleName',
                ))
                ->addColumn(array(
                    'label' => 'Name',
                    'index' => 'personName',
                ))
                ->addColumn(array(
                    'label' => 'Balance',
                    'index' => 'balance',
                    'align' => 'right',
                    'render' => 'money',
                    'order' => false,
                ))
                ->addColumn(array(
                    'label' => 'Username',
                    'index' => 'username',
                    'render' => array(
                        'type' => 'link',
                        'href' => 'http://www.mdnsolutions.com',
                    ),
                ))
                ->addColumn(array(
                    'label' => 'Birthday',
                    'index' => 'birthday',
                    'align' => 'center',
                    'render' => 'date',
                ))
                ->addColumn(array(
                    'label' => 'Last access',
                    'index' => 'lastAccess',
                    'render' => 'dateTime',
                ))
                ->addColumn(array(
                    'label' => 'Status',
                    'index' => 'statusId',
                    'render' => 'EnableOrDisabled',
                ))
                ->setRecordsPerPage(30)
                ->setOrder(false);
    }

}