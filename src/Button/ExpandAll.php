<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Ushkov Nikolai <ushkov@mte-telecom.ru>
 * Date: 19.04.16
 * Time: 13:45
 */

namespace Nnx\DataGrid\Button;

/**
 * Class ExpandAll
 * @package Nnx\DataGrid\Button
 */
class ExpandAll extends Simple
{
    protected $name ='expandall';

    protected $title = 'Развернуть все';

    protected $attributes = [
        'class' => 'btn btn-default',
        'id' => 'expand-all'
    ];

    protected $url = '#';

    protected $js =
        '$("a#expand-all").on("click", function () {
            NNX.jqGrid.expandAll($("#grid-%gridName%"));
            return false;
        });';
}
