<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Ushkov Nikolai <ushkov@mte-telecom.ru>
 * Date: 19.04.16
 * Time: 13:45
 */

namespace Nnx\DataGrid\Button;

/**
 * Class CollapseAll
 * @package Nnx\DataGrid\Button
 */
class CollapseAll extends Simple
{
    protected $name ='collapseall';

    protected $title = 'Свернуть все';

    protected $attributes = [
        'class' => 'btn btn-default',
        'id' => 'collapse-all'
    ];

    protected $url = '#';

    protected $js =
        '$("a#collapse-all").on("click", function () {
            NNX.jqGrid.collapseAll($("#grid-%gridName%"));
            return false;
        });';
}
