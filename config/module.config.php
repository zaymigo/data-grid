<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid;

use MteGrid\Grid\Column\GridColumnProviderInterface;


return array_merge(
    require 'serviceManager.config.php',
    require 'gridColumns.config.php',
    [
    'service_listener_options' => [
        [
            'service_manager' => 'GridColumnManager',
            'config_key' => 'grid_columns',
            'interface' => GridColumnProviderInterface::class,
            'method' => 'getGridColumnConfig'
        ]
    ]
]);