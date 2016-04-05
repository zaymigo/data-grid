<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Mutator;

use Nnx\DataGrid\Row;

/**
 * Class Highlight
 * @package Nnx\DataGrid\Mutator
 */
class Highlight extends AbstractMutator implements HighlightMutatorInterface
{
    /**
     * CSS class который осуществляет подсветку строки
     * @var string
     */
    protected $highlightCssClass;

    /**
     * Ключ который добавляется в данные для подсветки
     * @var string
     */
    protected $dataName = 'highlightCssClass';

    /**
     * Конструктор класса
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);
        if (array_key_exists('highlightCssClass', $options)) {
            $this->setHighlightCssClass($options['highlightCssClass']);
        }
        if (array_key_exists('dataName', $options)) {
            $this->setDataName($options['dataName']);
        }
    }

    /**
     * Изменяет данные
     * @param Row $value
     * @return mixed
     */
    public function change($value)
    {
        if (is_array($value)
            || $value instanceof Row
        ) {
            $value[$this->getDataName()] = $this->getHighlightCssClass();
        }
        return $value;
    }

    /**
     * Возвращает css class который установится для элемента
     * @return string
     */
    public function getHighlightCssClass()
    {
        return $this->highlightCssClass;
    }

    /**
     * Устанавливает css class который установится для элемента
     * @param string $highlightCssClass
     * @return $this
     */
    public function setHighlightCssClass($highlightCssClass)
    {
        $this->highlightCssClass = $highlightCssClass;
        return $this;
    }

    /**
     * Возвращает имя для элемента массива в который запишется
     * класс css стиля
     * @return string
     */
    public function getDataName()
    {
        return $this->dataName;
    }

    /**
     * Устанавливает имя для элемента массива в который запишется
     * класс css стиля
     * @param string $dataName
     * @return $this
     */
    public function setDataName($dataName)
    {
        $this->dataName = $dataName;
        return $this;
    }
}
