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
     * Возвращает данные строки для которой применяется мутатор.
     * @return array|ArrayAccess
     */
    public function getRowData();

    /**
     * Функция устанавливает данные строки в мутатор.
     * @param array|ArrayAccess $rowData
     * @return $this
     */
    public function setRowData($rowData);

    /**
     * Функция определяющая использовать мутатор или нет.
     * Если возвращает true мутатор применяется.
     * @return bool
     */
    public function validate();
}
