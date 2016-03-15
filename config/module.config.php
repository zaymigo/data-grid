<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid;


use MteGrid\Grid\Adapter\DoctrineDBAL;

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
        ]
    ],
    require 'grid.config.php',
    require 'serviceManager.config.php',
    require 'assetic.config.php',
    require 'viewHelpers.config.php'
);