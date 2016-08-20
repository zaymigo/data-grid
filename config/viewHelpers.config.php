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
use Nnx\DataGrid\View\Helper\JqGrid\GridButton;


return [
    'view_helpers' => [
        'invokables' => [
            'nnxGridJqGrid' => Grid::class,
            'nnxGridJqGridText' => Text::class,
            'nnxGridJqGridHidden' => Hidden::class,
            'nnxGridJqGridLink' => Link::class,
            'nnxGridJqGridAction' => Action::class,
            'nnxGridJqGridMoney' => Money::class,
            'nnxGridJqGridButton' => GridButton::class
        ],
    ]
];
