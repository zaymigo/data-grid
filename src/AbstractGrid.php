<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid;

use MteGrid\Grid\Column\ColumnInterface;
use MteGrid\Grid\Adapter\AdapterInterface;
use MteGrid\Grid\Column\GridColumnPluginManagerAwareInterface;
use MteGrid\Grid\Column\GridColumnPluginManagerAwareTrait;
use Traversable;
use ArrayAccess;

/**
 * Class AbstractGrid 
 * @package MteGrid\Grid
 */
abstract class AbstractGrid implements GridInterface, GridColumnPluginManagerAwareInterface
{
    use GridColumnPluginManagerAwareTrait;

    /**
     * Условия для фильтрации выбираемых данных
     * @var array | Traversable
     */
    protected $conditions;

    /**
     * Адаптер с помощью которого будет осуществляться выборка данных
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * Коллекция колонок таблицы
     * @var array | Traversable
     */
    protected $columns;

    /**
     * Опции таблицы
     * @var array | Traversable
     */
    protected $options;

    /**
     * Имя таблицы
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @param array | ArrayAccess $options
     * @throws Exception\InvalidArgumentException
     */
    public function __construct(array $options = [])
    {
        if (!array_key_exists('adapter', $options) || !$options['adapter']) {
            throw new Exception\InvalidArgumentException(
                'Для корректной работы таблиц в конструктор необходимо передавать адаптер.'
            );
        }
        $adapter = $options['adapter'];
        unset($options['adapter']);
        $name = array_key_exists('name', $options) ? $options['name'] : null;
        unset($options['name']);
        $this->configure($name, $adapter, $options);
    }

    /**
     * Конфигурируем адаптер грида
     * @param string $name
     * @param AdapterInterface $adapter
     * @param array $options \
     */
    protected function configure($name, AdapterInterface $adapter, array $options = [])
    {
        $this->setName($name);
        $this->setAdapter($adapter);
        $this->setOptions($options);
    }


    /**
     * Условия для фильтрации выбираемых данных
     * @param array | Traversable $conditions
     * @return $this
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
        return $this;
    }

    /**
     * Возвращает набор условий по которым фильтровать выборку
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * Устанавливает адаптер
     * @param AdapterInterface  $adapter
     * @return $this
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Возвращает адаптер с помощью которого будет осуществляться выборка данных
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Возвращает коллекцию колонок
     * @return array | Traversable
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Добавление колонок в таблицу
     * @param array | Traversable $columns
     * @return $this
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * Добавление колонки в таблицу
     * @param ColumnInterface | array | ArrayAccess $column
     * @return $this
     * @throws Exception\InvalidArgumentException
     */
    public function add($column)
    {
        if (is_array($column) || $column instanceof ArrayAccess) {
            /**
             * @TODO переделать чтобы вызывалось через ServiceLocator
             *
             *
             * @var Column\Factory $columnFactory
             */
            $columnFactory = new Column\Factory();
            $columnFactory->setColumnPluginManager($this->getColumnPluginManager());
            $column = $columnFactory->create($column);
        } elseif (!$column instanceof ColumnInterface) {
            throw new Exception\InvalidArgumentException(
                sprintf('Column должен быть массивом или реализовывать %s', ColumnInterface::class)
            );
        }
        $this->columns[$column->getName()] = $column;
        return $this;
    }

    /**
     * Возвращает колонку грида
     * @param string $name
     * @return ColumnInterface
     * @throws Exception\InvalidArgumentException
     */
    public function get($name)
    {
        if (!is_string($name)) {
            throw new Exception\InvalidArgumentException('Имя получаемой колонки должно быть строкой.');
        }
        $column = null;
        if (array_key_exists($name, $this->columns)) {
            $column = $this->columns[$name];
        }
        return $column;
    }

    /**
     * Опции таблицы
     * @param array | Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Возвращает массив опции таблицы
     * @return array | Traversable
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Устанавливает имя таблицы
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Возвращает имя таблицы
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Возвращает атрибуты используемые при отображении грида
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Устанавливает используемые для отображения грида
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Функция инициализации колонок
     * @return void
     */
    abstract public function init();

    /**
     * Возвращает массив строк
     * @return array
     */
    abstract public function getRowset();


}
