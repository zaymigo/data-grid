<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */
namespace Nnx\DataGrid;

use Nnx\DataGrid\View\Helper\JqGrid\Column\Action;
use Nnx\DataGrid\View\Helper\JqGrid\Column\Hidden;
use Nnx\DataGrid\View\Helper\JqGrid\Column\Link;
use Nnx\DataGrid\View\Helper\JqGrid\Column\Money;
use Nnx\DataGrid\View\Helper\JqGrid\Column\Text;
use Nnx\DataGrid\View\Helper\JqGrid\Grid;


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