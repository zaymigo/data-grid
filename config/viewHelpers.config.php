<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */
namespace NNX\DataGrid;

use NNX\DataGrid\View\Helper\JqGrid\Column\Action;
use NNX\DataGrid\View\Helper\JqGrid\Column\Hidden;
use NNX\DataGrid\View\Helper\JqGrid\Column\Link;
use NNX\DataGrid\View\Helper\JqGrid\Column\Money;
use NNX\DataGrid\View\Helper\JqGrid\Column\Text;
use NNX\DataGrid\View\Helper\JqGrid\Grid;


return [
    'view_helpers' => [
        'invokables' => [
            'mteGridJqGrid' => Grid::class,
            'mteGridJqGridText' => Text::class,
            'mteGridJqGridHidden' => Hidden::class,
            'mteGridJqGridLink' => Link::class,
            'mteGridJqGridAction' => Action::class,
            'mteGridJqGridMoney' => Money::class
        ],
    ]
];