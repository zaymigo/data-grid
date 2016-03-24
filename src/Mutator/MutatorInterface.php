<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Mutator;

use \ArrayAccess;

/**
 * Interface MutatorInterface
 * @package MteGrid\Grid\Mutator
 */
interface MutatorInterface
{
    /**
     * Изменяет данные
     * @param mixed $value
     * @return mixed
     */
    public function change($value);

    /**
     * @return array|ArrayAccess
     */
    public function getRowData();

    /**
     * @param array|ArrayAccess $rowData
     * @return $this
     */
    public function setRowData($rowData);
}