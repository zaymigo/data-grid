<?php
/**
 * @company Platforma Soft, Ltd.
 * @author Andrey Poltanov <poltanov@platformasoft.ru>
 */

namespace Nnx\DataGrid\Mutator;

/**
 * Class Percent
 * Осуществляет вывод процентов от переданных значений.
 * @package Nnx\DataGrid\Mutator
 */
class Percent extends AbstractMutator
{
    /**
     * Знаменатель
     * @var string
     */
    protected $denominator = '';

    /**
     * Числитель
     * @var string
     */
    protected $numerator = '';

    /**
     * Шаблон для вывода
     * @var string
     */
    protected $pattern = '%.1f%%';

    /**
     * @param array $options
     * @throws Exception\RuntimeException
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);
        if (!array_key_exists('denominator', $options) || !is_string($options['denominator'])) {
            throw new Exception\RuntimeException('denominator не был передан или он не является строкой');
        }
        $this->setDenominator($options['denominator']);

        if (!array_key_exists('numerator', $options) || !is_string($options['numerator'])) {
            throw new Exception\RuntimeException('numerator не был передан или он не является строкой');
        }
        $this->setNumerator($options['numerator']);

        if (array_key_exists('pattern', $options) && is_string($options['pattern'])) {
            $this->setPattern($options['pattern']);
        }
    }

    /**
     * Выводит данные
     * @param mixed $value
     * @return mixed
     */
    public function change($value)
    {
        return sprintf($this->getPattern(), $this->getPercent());
    }

    /**
     * Возвращает проценты
     * @return float
     * @throws Exception\RuntimeException
     */
    public function getPercent()
    {
        if (!array_key_exists($this->getDenominator(), $this->getRowData())) {
            throw new Exception\RuntimeException('Знаменатель отсутствует в наборе данных');
        }
        $denominator = $this->parseNumber($this->getRowData()[$this->getDenominator()]);
        if (!array_key_exists($this->getNumerator(), $this->getRowData())) {
            throw new Exception\RuntimeException('Числитель отсутствует в наборе данных');
        }
        $numerator = $this->parseNumber($this->getRowData()[$this->getNumerator()]);
        if ($denominator == 0) {
            return 0;
        }
        return $numerator / $denominator * 100;
    }

    protected function parseNumber($number)
    {
        return (float)filter_var($number, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    /**
     * Возвращает наименование поля знаменателя
     * @return string
     */
    public function getDenominator()
    {
        return $this->denominator;
    }

    /**
     * Устанавливает наименование поля знаменателя
     * @param string $denominator
     * @return $this
     */
    public function setDenominator($denominator)
    {
        $this->denominator = $denominator;
        return $this;
    }

    /**
     * Возвращает наименование поля числителя.
     * @return string
     */
    public function getNumerator()
    {
        return $this->numerator;
    }

    /**
     * Устанавливает наименование поля числителя.
     * @param string $numerator
     * @return $this
     */
    public function setNumerator($numerator)
    {
        $this->numerator = $numerator;
        return $this;
    }

    /**
     * Возвращает шаблон для вывода
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Устанавливает шаблон для вывода
     * @param string $pattern
     * @return $this
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }
}
