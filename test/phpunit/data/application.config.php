<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

return [
    'modules' => [
        'DoctrineModule',
        'DoctrineORMModule',
        'Nnx\\DataGrid'
    ],
    'module_listener_options' => [
        'module_paths' => [
            'Nnx\\DataGrid' => __DIR__ . '/../../'
        ]
    ]
];
