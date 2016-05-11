<?php
/**
 * @company Platforma Soft, Ltd.
 * @author Andrey Poltanov <poltanov@platformasoft.ru>
 */

namespace Nnx\DataGrid\Mutator;

use Nnx\DataGrid\Mutator\Exception;

/**
 * Class StringFormat
 * Осуществляет форматированный вывод переданных значений.
 * @package Nnx\DataGrid\Mutator
 */
class StringFormat extends AbstractMutator
{
    /**
     * Поля для вывода
     * @var array
     */
    protected $fields = [];

    /**
     * Шаблон для вывода
     * @var string
     */
    protected $pattern = ' ';

    /**
     * Конструктор класса
     * @param array $options
     * @throws Exception\RuntimeException
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        if (!array_key_exists('fields', $options) || !is_array($options['fields'])) {
            throw new Exception\RuntimeException('fields не был передан или он не является массивом');
        }
        $this->setFields($options['fields']);

        if (!array_key_exists('pattern', $options) || !is_string($options['pattern'])) {
            throw new Exception\RuntimeException('pattern не был передан или он не является строкой');
        }
        $this->setPattern($options['pattern']);
    }

    /**
     * Изменяет данные
     * @param mixed $value
     * @return mixed
     */
    public function change($value)
    {
        return vsprintf($this->getPattern(), $this->getFieldsValues());
    }

    /**
     * Возвращает массив значений полей, которые будут выведены
     * @return array
     */
    public function getFieldsValues()
    {
        $fields = $this->getFields();
        $row = $this->getRowData();
        $fields = array_fill_keys($fields, '');
        $res = array_intersect_key($row, $fields);
        return $res;
    }

    /**
     * Возвращает массив имен полей, значения которых нужно вывести
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Устанавливает набор имен полей которые будут выведены
     * @param array $fields
     * @return $this
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Возвращает шаблон для вывода значений полей.
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Устанавливает шаблон для вывода значений.
     * @param string $pattern
     * @return $this
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }
}