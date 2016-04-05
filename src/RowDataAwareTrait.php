<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid;

use ArrayAccess;

/**
 * Trait RowDataAwareTrait
 * @package Nnx\DataGrid
 */
trait RowDataAwareTrait
{
    /**
     * @var array|ArrayAccess
     */
    protected $rowData;

    /**
     * Возвращает набор данных строки выбранной посредством adapter'a
     * @return array|ArrayAccess
     */
    public function getRowData()
    {
        return $this->rowData;
    }

    /**
     * Устанавливает набор данных строки выбранных посредством adapter'a
     * @param array|ArrayAccess $rowData
     * @return $this
     */
    public function setRowData($rowData)
    {
        $this->rowData = $rowData;
        return $this;
    }
}
