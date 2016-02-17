<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

return [
    'modules' => [
        'MteGrid\\Grid'
    ],
    'module_listener_options' => [
        'module_paths' => [
            'MteGrid\\Grid' => __DIR__ . '/../../'
        ]
    ]
];