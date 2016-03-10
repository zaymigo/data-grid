<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Condition;

/**
 * Interface ConditionInterface
 * @package MteGrid\Grid
 */
interface ConditionInterface
{
    /**
     * Возвращает условие по которому осуществляется фильтрация (=, >, < и т.д.)
     * @return int
     */
    public function getCriteria();

    /**
     * Устанавливает условие по которому осуществляется фильтрация
     * (=, >, < и т.д.)
     * @param int $criteria
     * @return mixed
     */
    public function setCriteria($criteria);

    /**
     * Возвращает ключ по которому осуществляется фильтрация
     * @return string
     */
    public function getKey();

    /**
     * Устанавливает ключ по которому осуществляется фильтрация
     * @param string $key
     * @return $this
     */
    public function setKey($key);

    /**
     * Значение критерия
     * @return mixed
     */
    public function getValue();

    /**
     * Устанавливает значения для критерия
     * @param mixed $value
     * @return $this
     */
    public function setValue($value);
}