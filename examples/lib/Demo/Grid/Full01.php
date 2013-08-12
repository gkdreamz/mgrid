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
class Full01 extends Grid
{
    /**
     * Status Enabled
     */
    const ENABLED = 1;
    /**
     * Status Disabled
     */
    const DISABLED = 2;

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
                    'render' => array(
                        'type' => 'text',
                        'prefix' => 'No. ',
                    ),
                    'filter' => array(
                        'render' => array(
                            'type' => 'number',
                            'range' => 1,
                        ),
                    ),
                ))
                ->addColumn(array(
                    'label' => 'Company',
                    'index' => 'companyName',
                    'filter' => array(
                        'render' => array(
                            'type' => 'text',
                        ),
                    ),
                ))
                ->addColumn(array(
                    'label' => 'Role',
                    'index' => 'roleName',
                    'filter' => array(
                        'render' => array(
                            'type' => 'text',
                            'condition' => array('match' => array('fulltext')),
                        )
                    ),
                ))
                ->addColumn(array(
                    'label' => 'Name',
                    'index' => 'personName',
                    'filter' => array(
                        'render' => array(
                            'type' => 'text',
                            'condition' => array('match' => array('fulltext')),
                        )
                    ),
                ))
                ->addColumn(array(
                    'label' => 'Balance',
                    'index' => 'balance',
                    'align' => 'right',
                    'render' => 'money',
                    'order' => false,
                    'filter' => array(
                        'render' => array(
                            'type' => 'number',
                            'range' => 1,
                        ),
                    ),
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
                    'filter' => array(
                        'render' => array(
                            'type' => 'date',
                            'range' => 1,
                        )
                    ),
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
                    'filter' => array(
                        'render' => array(
                            'type' => 'select',
                            'attributes' => array(
                                'multiOptions' => array(
                                    Full01::ENABLED => 'Enable',
                                    Full01::DISABLED => 'Disabled',
                                ),
                            ),
                        ),
                    ),
                ))
                ->addAction(array(
                    'label' => 'Details',
                    'href' => 'http://mgrid.mdnsolutions.com?your_action=details',
                    'target' => '_blank',
                    'params' => array(
                        'myid' => 'id',
                    ),
                    'cssClass' => 'view',
                ))
                ->addAction(array(
                    'label' => 'Edit',
                    'href' => '?your_action=edit',
                    'params' => array(
                        'myid' => 'id',
                        'edit' => 1,
                    ),
                ))
                ->addAction(array(
                    'label' => 'Disable Record',
                    'href' => '?your_action=disable_status',
                    'params' => array(
                        'myid' => 'id',
                        'disable' => 'yes',
                    ),
                    'condition' => function ($row) {
                        return ($row['statusId'] == Full01::ENABLED);
                    },
                ))
                ->addAction(array(
                    'label' => 'Enable Record',
                    'href' => '?your_action=disable_status',
                    'params' => array(
                        'myid' => 'id',
                        'enable' => 'yes',
                    ),
                    'condition' => function ($row) {
                        return ($row['statusId'] == Full01::DISABLED);
                    },
                ))
                ->addAction(array(
                    'label' => 'Delete',
                    'href' => '?your_action=delete',
                    'cssClass' => 'del',
                    'params' => array(
                        'myid' => 'id',
                        'delete' => '1',
                    ),
                ));
//                ->setFilter(false);
//                ->setPager(false);
//                ->setHeader(false);
//                ->setOrder(false);
    }

}