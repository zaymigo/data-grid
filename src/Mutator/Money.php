<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace NNX\DataGrid\Mutator;

/**
 * Class Money
 * @package NNX\DataGrid\Mutator
 *
 * Класс для мутации данных чисел. Приводит числа к денежному формату
 */
class Money extends AbstractMutator
{
    /**
     * Количество знаков после запятой
     * @var int
     */
    protected $decimals = 2;

    /**
     * Разделитель десятых
     * @var string
     */
    protected $decPoint = '.';

    /**
     * Разделитель сотен
     * @var string
     */
    protected $thousandSeparator = ' ';

    /**
     * Конструктор класса
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);
        if (array_key_exists('decimals', $options)) {
            $this->setDecimals((int)$options['decimals']);
        }
        if (array_key_exists('decPoint', $options)) {
            $this->setDecPoint((string)$options['decPoint']);
        }
        if (array_key_exists('thousandSeparator', $options)) {
            $this->setThousandSeparator((string)$options['thousandSeparator']);
        }
    }

    /**
     * Изменяет данные
     * @param mixed $value
     * @return mixed
     */
    public function change($value)
    {
        return number_format($value, $this->getDecimals(), $this->getDecPoint(), $this->getThousandSeparator());
    }

    /**
     * Возвращает значение decimals для числа
     * @return int
     */
    public function getDecimals()
    {
        return $this->decimals;
    }

    /**
     * Устанавливает значение decimals для числа
     * @param int $decimals
     * @return $this
     */
    public function setDecimals($decimals)
    {
        $this->decimals = $decimals;
        return $this;
    }

    /**
     * Возвращает разделитель
     * @return string
     */
    public function getThousandSeparator()
    {
        return $this->thousandSeparator;
    }

    /**
     * Устанавливает разделитель
     * @param string $thousandSeparator
     * @return $this
     */
    public function setThousandSeparator($thousandSeparator)
    {
        $this->thousandSeparator = $thousandSeparator;
        return $this;
    }

    /**
     * Возвращает DecPoint
     * @return string
     */
    public function getDecPoint()
    {
        return $this->decPoint;
    }

    /**
     * Устанавливает DecPoint
     * @param string $decPoint
     * @return $this
     */
    public function setDecPoint($decPoint)
    {
        $this->decPoint = $decPoint;
        return $this;
    }
}
