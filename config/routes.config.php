<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */
return [
    'router' => [
        'routes' => [
            'NNXCompanyGrid' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/grid',
                    'defaults' => [
                        'controller' => 'MteGrid\Grid\Controller\Data',
                        'action' => 'get',
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9].*'
                    ],
                ]
            ]
        ]
    ]
];