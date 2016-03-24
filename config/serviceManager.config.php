<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */
namespace MteGrid\Grid;

use MteGrid\Grid\Column\GridColumnPluginManager;
use MteGrid\Grid\Column\GridColumnPluginManagerFactory;
use MteGrid\Grid\Mutator\GridMutatorPluginManagerFactory;
use MteGrid\Grid\Mutator\GridMutatorPluginManager;
use MteGrid\Grid\Options\ModuleOptions;

return [
    'service_manager' => [
        'abstract_factories' => [

        ],
        'factories' => [
            GridColumnPluginManager::class => GridColumnPluginManagerFactory::class,
            GridMutatorPluginManager::class => GridMutatorPluginManagerFactory::class,
            GridPluginManager::class => GridPluginManagerFactory::class,
            ModuleOptions::class => Options\Factory::class
        ],
        'invokables' => [
            Adapter\Factory::class => Adapter\Factory::class
        ],
        'aliases' => [
            'GridMutatorManager' => GridMutatorPluginManager::class,
            'GridColumnManager' => GridColumnPluginManager::class,
            'GridManager' => GridPluginManager::class,
            'GridModuleOptions' => ModuleOptions::class
        ]
    ]
];