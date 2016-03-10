<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid;


return array_merge(
    [
        'service_listener_options' => [
            [

            ],
        ],
    ],
    require 'serviceManager.config.php',
    require 'grid.config.php',
    require 'assetic.config.php');