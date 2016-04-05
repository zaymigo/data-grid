<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Column;

/**
 * Class Money
 * @package Nnx\DataGrid\Column
 */
class Money extends AbstractColumn
{
    /**
     * Предустановленные мутаторы
     * @var array
     */
    protected $invokableMutators = [
        'money'
    ];
}
