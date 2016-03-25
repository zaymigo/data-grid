<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Mutator;

/**
 * Class Money
 * @package MteGrid\Grid\Mutator
 */
class Money extends AbstractMutator
{
    /**
     * @var int
     */
    protected $decimals = 2;

    /**
     * @var string
     */
    protected $decPoint = '.';

    /**
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
     * @return int
     */
    public function getDecimals()
    {
        return $this->decimals;
    }

    /**
     * @param int $decimals
     * @return $this
     */
    public function setDecimals($decimals)
    {
        $this->decimals = $decimals;
        return $this;
    }

    /**
     * @return string
     */
    public function getThousandSeparator()
    {
        return $this->thousandSeparator;
    }

    /**
     * @param string $thousandSeparator
     * @return $this
     */
    public function setThousandSeparator($thousandSeparator)
    {
        $this->thousandSeparator = $thousandSeparator;
        return $this;
    }

    /**
     * @return string
     */
    public function getDecPoint()
    {
        return $this->decPoint;
    }

    /**
     * @param string $decPoint
     * @return $this
     */
    public function setDecPoint($decPoint)
    {
        $this->decPoint = $decPoint;
        return $this;
    }
}
