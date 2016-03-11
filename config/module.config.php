<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid;


use MteGrid\Grid\Adapter\DoctrineDBAL;

return array_merge(
    [
        'mte-grid' => [
            'grids' => [
                'SimpleGrid' => [
                    'class' => SimpleGrid::class,
                    'options' => [
                        'adapter' => [
                            'class' => DoctrineDBAL::class,
                            'options' => []
                        ],
                    ]
                ],
            ]
        ]
    ],
    require 'grid.config.php',
    require 'serviceManager.config.php',
    require 'assetic.config.php');