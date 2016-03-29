<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid;

use ArrayAccess;

/**
 * Interface RowDataAwareInterface
 * @package MteGrid\Grid\Column
 */
interface RowDataAwareInterface
{
    /**
     * Возвращает данные строки
     * @return array|ArrayAccess
     */
    public function getRowData();

    /**
     * Устанавливает данные строки
     * @param array|ArrayAccess $data
     * @return $this
     */
    public function setRowData($data);
}
