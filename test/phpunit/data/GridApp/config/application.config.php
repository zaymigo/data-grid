<?php

use Nnx\DataGrid\Test\PhpUnit\TestData\TestPath;
use Nnx\DataGrid\Module;
use Nnx\DataGrid\Test\PhpUnit\TestData\GridApp;
use Nnx\ZF2TestToolkit\Listener\InitTestAppListener;
use Nnx\ZF2TestToolkit\Listener\StopDoctrineLoadCliPostEventListener;

return [
    'modules'                 => [
        'DoctrineModule',
        'DoctrineORMModule',
        Module::MODULE_NAME,
        GridApp\TestModule1\Module::MODULE_NAME,
    ],
    'module_listener_options' => [
        'module_paths'      => [
            Module::MODULE_NAME => TestPath::getPathToModule(),
            GridApp\TestModule1\Module::MODULE_NAME => __DIR__ . '/../module/TestModule1'
        ],
        'config_glob_paths' => [
            __DIR__ . '/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ],
    'service_manager'         => [
        'invokables' => [
            InitTestAppListener::class => InitTestAppListener::class,
            StopDoctrineLoadCliPostEventListener::class => StopDoctrineLoadCliPostEventListener::class
        ]
    ],
    'listeners'               => [
        InitTestAppListener::class,
        StopDoctrineLoadCliPostEventListener::class
    ]
];
