<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Ushkov Nikolai <ushkov@mte-telecom.ru>
 * Date: 19.04.16
 * Time: 13:45
 */

namespace Nnx\DataGrid\Button;

/**
 * Class ShowHideColums
 * @package Nnx\DataGrid\Button
 */
class ShowHideColumns extends Simple
{
    protected $name ='showhidecolums';

    protected $title = 'Столбцы';

    protected $attributes = [
        'class' => 'btn btn-default',
        'id' => 'show-hide-columns'
    ];

    protected $url = '#';

    protected $js =
        '$("a#show-hide-columns").on("click", function () {
            $("#grid-%gridName%").jqGrid("columnChooser",{
                jqModal : true,
                done: function(perm) {
                    NNX.jqGrid.saveColums($(this),perm);
                }
            });
            return false;
        });';
}
