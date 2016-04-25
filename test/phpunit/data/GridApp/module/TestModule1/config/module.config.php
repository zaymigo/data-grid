<?php

use Nnx\DataGrid\Test\PhpUnit\TestData\GridApp;

return [
    'doctrine' => [
        'driver' => [
            GridApp\TestModule1\Module::MODULE_NAME => [
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity'],
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
            ],
        ]
    ]
];