<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */
namespace MteGrid\Grid;

use MteGrid\Grid\Column\GridColumnPluginManager;
use MteGrid\Grid\Column\GridColumnPluginManagerFactory;

return [
    'service_manager' => [
        'factories' => [
            GridColumnPluginManager::class => GridColumnPluginManagerFactory::class
        ],
        'aliases' => [
            'GridColumnManager' => GridColumnPluginManager::class
        ]
    ]
];