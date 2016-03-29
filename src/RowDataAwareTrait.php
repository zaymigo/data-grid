<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid;

use ArrayAccess;

/**
 * Trait RowDataAwareTrait
 * @package MteGrid\Grid
 */
trait RowDataAwareTrait
{
    /**
     * @var array|ArrayAccess
     */
    protected $rowData;

    /**
     * @return array|ArrayAccess
     */
    public function getRowData()
    {
        return $this->rowData;
    }

    /**
     * @param array|ArrayAccess $rowData
     * @return $this
     */
    public function setRowData($rowData)
    {
        $this->rowData = $rowData;
        return $this;
    }
}
