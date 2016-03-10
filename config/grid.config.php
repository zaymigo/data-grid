<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */
namespace MteGrid\Grid;

use MteGrid\Grid\Adapter\DoctrineDBAL;

return [
    'grid_manager' => [
        'abstract_factories' => [
            AbstractGridManagerFactory::class
        ],
    ],
    'grid_columns' => [

    ],
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
];