<?php
namespace Nnx\DataGrid\Test\PhpUnit\TestData\GridApp;


use Nnx\DataGrid\Test\PhpUnit\TestData\TestPath;
use Nnx\DataGrid\Test\PhpUnit\TestData\GridApp;

return [
    'doctrine' => [
        'entitymanager' => [
            'test' => [
                'configuration' => 'test',
                'connection'    => 'test',
            ]
        ],
        'connection' => [
            'test' => [
                'configuration' => 'test',
                'eventmanager'  => 'orm_default',
            ]
        ],
        'configuration' => [
            'test' => [
                'metadata_cache'    => 'array',
                'query_cache'       => 'array',
                'result_cache'      => 'array',
                'hydration_cache'   => 'array',
                'driver'            => 'test',
                'generate_proxies'  => true,

                'proxy_dir'         => TestPath::getPathToDoctrineProxyDir(),
                'proxy_namespace'   => 'DoctrineORMModule\Proxy',
                'filters'           => [],
                'datetime_functions' => [],
                'string_functions' => [],
                'numeric_functions' => [],
                'second_level_cache' => []
            ]
        ],
        'driver' => [
            'test' => [
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => [
                    GridApp\TestModule1\Module::MODULE_NAME . '\\Entity' => GridApp\TestModule1\Module::MODULE_NAME,
                ]
            ],
            'orm_default' => [
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => [
                    GridApp\TestModule1\Module::MODULE_NAME . '\\Entity' => GridApp\TestModule1\Module::MODULE_NAME,
                ]
            ]
        ]
    ],
];