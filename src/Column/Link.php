<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Column;

/**
 * Class Link
 * @package Nnx\DataGrid\Column
 */
class Link extends AbstractColumn
{
    /**
     * @var array
     */
    protected $invokableMutators = [
        'link'
    ];
}
