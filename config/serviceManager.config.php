<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */
namespace Nnx\DataGrid;

use Nnx\DataGrid\Column\GridColumnPluginManager;
use Nnx\DataGrid\Column\GridColumnPluginManagerFactory;
use Nnx\DataGrid\Mutator\GridMutatorPluginManagerFactory;
use Nnx\DataGrid\Mutator\GridMutatorPluginManager;
use Nnx\DataGrid\Options\ModuleOptions;

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
            Adapter\Factory::class => Adapter\Factory::class,
            Column\Header\Factory::class => Column\Header\Factory::class
        ],
        'aliases' => [
            'GridMutatorManager' => GridMutatorPluginManager::class,
            'GridColumnManager' => GridColumnPluginManager::class,
            'GridManager' => GridPluginManager::class,
            'GridModuleOptions' => ModuleOptions::class
        ]
    ]
];