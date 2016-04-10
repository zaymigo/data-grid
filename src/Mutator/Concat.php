<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Mutator;

/**
 * Class Concat
 * @package Nnx\DataGrid\Mutator
 */
class Concat extends AbstractMutator
{
    /**
     * Поля для объединения
     * @var array
     */
    protected $concatenateFields = [];

    /**
     * Разделитель для объединяемых значений
     * @var string
     */
    protected $separator = ' ';

    /**
     * Конструктор класса
     * @param array $options
     * @throws Exception\RuntimeException
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        if (!array_key_exists('concatenateFields', $options) || !$options['concatenateFields']) {
            throw new Exception\RuntimeException(
                'Не заданы поля для конкатенации. Необходимо указать массив concatenateFields'
            );
        }

        $this->setConcatenateFields($options['concatenateFields']);

        if (array_key_exists('separator', $options) && $options['separator']) {
            $this->setSeparator($options['separator']);
        }
    }


    /**
     * Изменяет данные
     * @param mixed $value
     * @return mixed
     */
    public function change($value)
    {
        $concatenateFields = $this->getConcatenateFields();
        $row = $this->getRowData();
        $res = [];
        foreach ($concatenateFields as $field) {
            if (array_key_exists($field, $row)) {
                $res[] = $row[$field];
            }
        }
        return implode($this->getSeparator(), $res);
    }

    /**
     * Возвращает массив имен полей которые будут объединены
     * @return array
     */
    public function getConcatenateFields()
    {
        return $this->concatenateFields;
    }

    /**
     * Устанавливает набор имен полей которые будут объединены
     * @param array $concatenateFields
     * @return $this
     */
    public function setConcatenateFields($concatenateFields)
    {
        $this->concatenateFields = $concatenateFields;
        return $this;
    }

    /**
     * Возвращает разделитель объединяемых значений полей.
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     * Устанавливает разделитель объединяемых значений.
     * @param string $separator
     * @return $this
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;
        return $this;
    }

}