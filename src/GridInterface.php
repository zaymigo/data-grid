<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace NNX\DataGrid;

use NNX\DataGrid\Adapter\AdapterInterface;
use NNX\DataGrid\Column\ColumnInterface;
use Traversable;
use ArrayAccess;
use Zend\Stdlib\InitializableInterface;

/**
 * Interface GridInterface
 * @package NNX\DataGrid
 */
interface GridInterface extends InitializableInterface
{
    /**
     * Условия для фильтрации выбираемых данных
     * @param array | Traversable $conditions
     * @return $this
     */
    public function setConditions($conditions);

    /**
     * Возвращает набор условий по которым фильтровать выборку
     * @return array
     */
    public function getConditions();

    /**
     * Устанавливает адаптер
     * @param AdapterInterface $adapter
     * @return $this
     */
    public function setAdapter($adapter);

    /**
     * Возвращает адаптер с помощью которого будет осуществляться выборка данных
     * @return AdapterInterface
     */
    public function getAdapter();

    /**
     * Возвращает коллекцию колонок
     * @return array | Traversable
     */
    public function getColumns();

    /**
     * Добавление колонок в таблицу
     * @param array | Traversable $columns
     * @return $this
     */
    public function setColumns($columns);

    /**
     * Добавление колонки в таблицу
     * @param ColumnInterface | array | ArrayAccess $column
     * @return $this
     */
    public function add($column);

    /**
     * Возвращает колонку грида
     * @param string $name
     * @return ColumnInterface
     */
    public function get($name);

    /**
     * Опции таблицы
     * @param array | Traversable $options
     * @return $this
     */
    public function setOptions($options);

    /**
     * Возвращает массив опции таблицы
     * @return array | Traversable
     */
    public function getOptions();

    /**
     * Устанавливает имя таблицы
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Возвращает имя таблицы
     * @return string
     */
    public function getName();

    /**
     * Возвращает атрибуты используемые при отображении грида
     * @return array
     */
    public function getAttributes();

    /**
     * Устанавливает используемые для отображения таблицы атрибуты
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes);

    /**
     * Добавляет атрибут таблицы
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function addAttribute($key, $value);

    /**
     * Возвращает массив строк
     * @return array
     */
    public function getRowSet();
}
