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
                    'head_jqgrid_css' => [
                        'assets' => [
                            'css/jqgrid/ui.jqgrid.css'
                        ]
                    ],
                    'head_jqgrid_js' => [
                        'assets' => [
                            'js/jqgrid/i18n/grid.locate-ru.js',
                            'js/jqgrid/jquery.jqGrid.min.js',
                        ]
                    ],
                ],
            ],
        ]
    ]
];