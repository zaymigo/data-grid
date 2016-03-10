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
        'abstract_factories' => [

        ],
        'factories' => [
            GridColumnPluginManager::class => GridColumnPluginManagerFactory::class,
            GridPluginManager::class => GridPluginManagerFactory::class,
            Adapter\Factory::class => Adapter\Factory::class
        ],
        'aliases' => [
            'GridColumnManager' => GridColumnPluginManager::class,
            'GridManager' => GridPluginManager::class
        ]
    ]
];