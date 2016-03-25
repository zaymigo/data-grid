<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */
namespace MteGrid\Grid;

use MteGrid\Grid\View\Helper\JqGrid\Column\Action;
use MteGrid\Grid\View\Helper\JqGrid\Column\Hidden;
use MteGrid\Grid\View\Helper\JqGrid\Column\Link;
use MteGrid\Grid\View\Helper\JqGrid\Column\Text;
use MteGrid\Grid\View\Helper\JqGrid\Grid;


return [
    'view_helpers' => [
        'invokables' => [
            'mteGridJqGrid' => Grid::class,
            'mteGridJqGridText' => Text::class,
            'mteGridJqGridHidden' => Hidden::class,
            'mteGridJqGridLink' => Link::class,
            'mteGridJqGridAction' => Action::class
        ],
    ]
];