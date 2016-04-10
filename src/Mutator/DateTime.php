<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Mutator;

use Zend\Filter\DateTimeFormatter;

/**
 * Class Date
 * @package Nnx\DataGrid\Mutator
 */
class DateTime extends AbstractMutator
{
    /**
     * Объект Zend\Filter\FateTimeFormatter
     * @var DateTimeFormatter
     */
    protected $dateTimeFormatter;

    /**
     * Формат даты и времени
     * @var string
     */
    protected $format = \DateTime::ISO8601;

    /**
     * Конструктор класса
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        if (array_key_exists('dateTimeFormatter', $options) && $options['dateTimeFormatter']) {
            if (!$options['dateTimeFormatter'] instanceof DateTimeFormatter) {
                throw new Exception\RuntimeException(sprintf(''));
            }
            $this->setDateTimeFormatter($options['dateTimeFormatter']);
        }

        if (array_key_exists('format', $options) && $options['format']) {
            $this->setFormat($options['format']);
        }
    }

    /**
     * Изменяет данные
     * @param mixed $value
     * @return mixed
     */
    public function change($value)
    {
        return $this->getDateTimeFormatter()->setFormat($this->getFormat())->filter($value);
    }

    /**
     * Возвращает объхект Zend\Filter\DateTimeFormatter для форматирования дат
     * @return \Zend\Filter\DateTimeFormatter
     */
    public function getDateTimeFormatter()
    {
        return $this->dateTimeFormatter;
    }

    /**
     * Устанавливает объхект Zend\Filter\DateTimeFormatter для форматирования дат
     * @param \Zend\Filter\DateTimeFormatter $dateTimeFormatter
     * @return $this
     */
    public function setDateTimeFormatter($dateTimeFormatter)
    {
        $this->dateTimeFormatter = $dateTimeFormatter;
        return $this;
    }

    /**
     * Возвращает формат даты и времени
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Устанавливает формат даты и времени
     * @param string $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }
}