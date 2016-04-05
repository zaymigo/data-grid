<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid;


use Nnx\DataGrid\Adapter\DoctrineDBAL;
use Nnx\DataGrid\Controller\DataController;

return array_merge(
    [
        'mteGrid' => [
            'doctrine_entity_manager' => 'doctrine.entitymanager.orm_default',
            'grids' => [
                'SimpleGrid' => [
                    'class' => SimpleGrid::class,
                    'options' => [
                        'adapter' => [
                            'class' => DoctrineDBAL::class,
                        ],
                    ]
                ],
            ]
        ],
        'controllers' => array(
            'invokables' => array(
                'Nnx\DataGrid\Controller\Data' => DataController::class,
            ),
        ),
        'view_manager' => [
            'template_map' => include __DIR__ . '/../template_map.php',
            'template_path_stack' => [
                'mteGridGrid' => __DIR__ . '/../view',
            ],
            'strategies' => [
                'ViewJsonStrategy'
            ],
            'controller_map' => [
                'Nnx\DataGrid' => 'Grid',
            ],
        ]
    ],
    require 'grid.config.php',
    require 'serviceManager.config.php',
    require 'assetic.config.php',
    require 'viewHelpers.config.php',
    require 'routes.config.php'
);