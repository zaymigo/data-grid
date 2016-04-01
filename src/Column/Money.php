<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace NNX\DataGrid\Column;

/**
 * Class Money
 * @package NNX\DataGrid\Column
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
