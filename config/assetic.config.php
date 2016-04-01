<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid;

return [
    'assetic_configuration' => [
        'modules' => [
            'MteGrid\Grid' => [
                'root_path' => __DIR__ . '/../assets',
                'collections' => [
                    'mtegrid_grid_images' => array(
                        'assets' => array(
                            'images/grid/*.png',
                            'images/grid/*.gif',
                        ),
                        'options' => array(
                            'move_raw' => true,
                        )
                    ),
                    'head_jqgrid_css' => [
                        'assets' => [
                            'css/jqgrid/jquery-ui.min.css',
                            'css/jqgrid/ui.jqgrid.css',
                            'css/jqgrid/wordwrap.css'
                        ]
                    ],
                    'head_jqgrid_js' => [
                        'assets' => [
                            'js/jqgrid/i18n/grid.locale-ru.js',
                            'js/jqgrid/jquery.jqGrid.min.js',
                            'js/jqgrid/jqGrid.extends.js',
                        ]
                    ],
                ],
            ],
        ]
    ]
];