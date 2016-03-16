<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Column;

use MteGrid\Grid\Column\Header\HeaderInterface;
use Traversable;

abstract class AbstractColumn implements ColumnInterface
{
    /**
     * @var HeaderInterface
     */
    protected $header;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array | Traversable
     */
    protected $options = [];

    /**
     * @var array | Traversable
     */
    protected $attributes = [];

    /**
     * По данному полю осуществляется сортировка колонок при выводе
     * @var int
     */
    protected $order;

    /**
     * Флаг сортировки данных
     * @var bool
     */
    protected $sortable;

    /**
     * Устанавливает заголовок для колонки
     * @param HeaderInterface | array | Traversable $header
     * @return $this
     */
    public function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * Возвращает объект заголовка для колонки
     * @return HeaderInterface
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Устанавливает путь до шаблона строки
     * @param string $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Возвраащет путь до шаблона
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Имя колонки
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Возвращает индекс по которому будут выбираться для колонки данные предоставленные адаптером
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Опции и настройки колонки
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Возвращает опции колонки
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Аттрибуты колонки
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes = [])
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Возвращает атрибуты колонки
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Возвращает параметр по которому сортируются колонки
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Устанавливает параметр для сортировки колонок
     * @param int $order
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Флаг сообщающий можно ли сортировать по колонке
     * @return bool
     */
    public function getSortable()
    {
        return $this->sortable;
    }

    /**
     * Устанавливает флаг информирующий можно сортировать или нет по колонке
     * @param bool $sortable
     * @return $this
     */
    public function setSortable($sortable)
    {
        $this->sortable = $sortable;
        return $this;
    }
}
