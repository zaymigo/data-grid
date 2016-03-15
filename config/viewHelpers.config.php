<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */
namespace MteGrid\Grid;

use MteGrid\Grid\View\Helper\JqGrid\Grid;
use MteGrid\Grid\View\Helper\JqGrid\Column;

return [
    'view_helpers' => [
        'invokables' => [
            'mteGridJqGrid' => Grid::class,
            'mteGridJqGridColumn' => Column::class
        ],
    ]
];