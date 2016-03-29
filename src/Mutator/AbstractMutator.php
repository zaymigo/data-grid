<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Mutator;

use ArrayAccess;
use MteGrid\Grid\RowDataAwareInterface;

/**
 * Class AbstractMutator
 * @package MteGrid\Grid\Mutator
 */
abstract class AbstractMutator implements MutatorInterface, RowDataAwareInterface
{
    /**
     * @var array|ArrayAccess
     */
    protected $rowData;

    public function __construct(array $options = [])
    {
        if (array_key_exists('rowData', $options) && $options['rowData']) {
            $this->setRowData($options['rowData']);
        }
    }

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

    /**
     * Изменяет данные
     * @param mixed $value
     * @return mixed
     */
    abstract public function change($value);
}
