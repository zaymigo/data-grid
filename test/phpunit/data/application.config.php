<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

return [
    'modules' => [
        'DoctrineModule',
        'DoctrineORMModule',
        'NNX\\DataGrid'
    ],
    'module_listener_options' => [
        'module_paths' => [
            'NNX\\DataGrid' => __DIR__ . '/../../'
        ]
    ]
];