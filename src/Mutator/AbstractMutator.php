<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Mutator;

use ArrayAccess;
use Nnx\DataGrid\RowDataAwareInterface;

/**
 * Class AbstractMutator
 * @package Nnx\DataGrid\Mutator
 */
abstract class AbstractMutator implements MutatorInterface, RowDataAwareInterface
{
    /**
     * @var array|ArrayAccess
     */
    protected $rowData;

    /**
     * Функция которая возвращает булеву. В случае true мутатор применяется.
     * @var callable|array|string
     */
    protected $validationFunction;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (array_key_exists('rowData', $options) && $options['rowData']) {
            $this->setRowData($options['rowData']);
        }
        if (array_key_exists('validate', $options) && $options['validate']) {
            $this->setValidationFunction($options['validate']);
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
     * @return array|callable|string
     */
    public function getValidationFunction()
    {
        return $this->validationFunction;
    }

    /**
     * @param array|callable|string $validationFunction
     * @return $this
     */
    public function setValidationFunction($validationFunction)
    {
        $this->validationFunction = $validationFunction;
        return $this;
    }

    /**
     * В случае возвращения true мутатор применяется.
     * @return bool
     */
    public function validate()
    {
        $res = true;
        if ($this->getValidationFunction()) {
            $res = call_user_func($this->getValidationFunction(), $this->getRowData());
        }
        return $res;
    }

    /**
     * Изменяет данные
     * @param mixed $value
     * @return mixed
     */
    abstract public function change($value);
}
