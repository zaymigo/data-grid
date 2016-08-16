<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid;

return [
    'assetic_configuration' => [
        'modules' => [
            'Nnx\DataGrid' => [
                'root_path' => __DIR__ . '/../assets',
                'collections' => [
                    'mtegrid_grid_images' => [
                        'assets' => [
                            'images/grid/*.png',
                            'images/grid/*.gif',
                        ],
                        'options' => [
                            'move_raw' => true,
                        ]
                    ],
                    'head_jquery_multiselect_css' => [
                        'assets' => [
//                            'css/multiselect/common.css',
                            'css/multiselect/ui.multiselect.css',
                        ]
                    ],
                    'head_jqgrid_css' => [
                        'assets' => [
                            'css/jqgrid/jquery-ui.min.css',
                            'css/jqgrid/ui.jqgrid.css',
                            'css/jqgrid/wordwrap.css'
                        ]
                    ],
                    'head_jquery_multiselect_js' => [
                        'assets' => [
                            'js/multiselect/plugins/localisation/jquery.localisation-min.js',
                            'js/multiselect/plugins/scrollTo/jquery.scrollTo-min.js',
                            'js/multiselect/ui.multiselect.js',
                            'js/multiselect/ui.multiselect.extended.js',
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
