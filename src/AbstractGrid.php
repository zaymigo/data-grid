<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid;

use Nnx\DataGrid\Column\ColumnInterface;
use Nnx\DataGrid\Adapter\AdapterInterface;
use Nnx\DataGrid\Column\GridColumnPluginManager;
use Nnx\DataGrid\Column\GridColumnPluginManagerAwareTrait;
use Nnx\DataGrid\Mutator\GridMutatorPluginManager;
use Nnx\DataGrid\Mutator\GridMutatorPluginManagerAwareTrait;
use Nnx\DataGrid\Mutator\MutatorInterface;
use Traversable;
use ArrayAccess;

/**
 * Class AbstractGrid
 * @package Nnx\DataGrid
 */
abstract class AbstractGrid implements GridInterface
{

    use GridColumnPluginManagerAwareTrait;
    use GridMutatorPluginManagerAwareTrait;
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
     * Массив атрибутов таблицы для отображения
     * @var array
     */
    protected $attributes = [];

    /**
     * Мутаторы для строк
     * @var array | ArrayAccess
     */
    protected $mutators = [];


    /**
     * Конструкто класса
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
        $this->setName($name);

        if (!array_key_exists('columnPluginManager', $options) || !$options['columnPluginManager']) {
            throw new Exception\InvalidArgumentException(
                'Для корректной работы таблиц должна передаваться GridColumnPluginManager в конструктор таблиц.'
            );
        }
        $columnPluginManager = $options['columnPluginManager'];
        unset($options['columnPluginManager']);

        if (!array_key_exists('mutatorPluginManager', $options) || !$options['mutatorPluginManager']) {
            throw new Exception\InvalidArgumentException(
                'Для корректной работы таблиц должна передаваться GridMutatorPluginManager.'
            );
        }
        $mutatorPluginManager = $options['mutatorPluginManager'];
        unset($options['mutatorPluginManager']);
        $this->configure($mutatorPluginManager, $adapter, $columnPluginManager);
        $this->setOptions($options);
    }

    /**
     * Конфигурируем адаптер грида
     * @param AdapterInterface $adapter
     * @param array $options
     */
    protected function configure(
        GridMutatorPluginManager $mutatorPluginManager,
        AdapterInterface $adapter,
        GridColumnPluginManager $columnPluginManager)
    {
        $this->setMutatorPluginManager($mutatorPluginManager);
        $this->setAdapter($adapter);
        $this->setColumnPluginManager($columnPluginManager);
    }


    /**
     * Условия для фильтрации выбираемых данных
     * @param array | Traversable $conditions
     * @return $this
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
        $this->getAdapter()->setConditions($conditions);
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
     * @param ColumnInterface|array|ArrayAccess $column
     * @return $this
     * @throws Exception\InvalidArgumentException
     */
    public function add($column)
    {
        if (is_array($column) || $column instanceof ArrayAccess) {
            if (!array_key_exists('type', $column)) {
                throw new Column\Exception\InvalidColumnException(
                    'Не передан тип создаваемого столбца.'
                );
            }
            $column['columnPluginManager'] = $this->getColumnPluginManager();
            if (!$this->getColumnPluginManager()->has($column['type'])) {
                throw new Exception\RuntimeException(sprintf('Колонка с именем %s не найдена', $column['type']));
            }
            /** @var ColumnInterface $column */
            $column = $this->getColumnPluginManager()->get($column['type'], $column);
        } elseif (!$column instanceof ColumnInterface) {
            throw new Exception\InvalidArgumentException(
                sprintf('Column должен быть массивом или реализовывать %s', ColumnInterface::class)
            );
        }
        $this->columns[$column->getName()] = $column;
        return $this;
    }

    /**
     * Удаляет колонку с именем $name из таблицы
     * @param string $name
     * @return $this
     */
    public function remove($name)
    {
        if (array_key_exists($name, $this->columns)) {
            unset($this->columns[$name]);
        }
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
     * Добавляет атрибут для таблицы
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function addAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * Возвращает набор мутаторов для строк таблицы
     * @return array|ArrayAccess
     */
    public function getMutators()
    {
        return $this->mutators;
    }

    /**
     * Устанавливает набор мутаторов для строк таблицы
     * @param array|ArrayAccess $mutators
     * @return $this
     */
    public function setMutators(array $mutators)
    {
        $this->mutators = $mutators;
        return $this;
    }

    /**
     * Добавляет мутатор для строк таблицы
     * @param MutatorInterface|array|ArrayAccess $mutator
     * @return $this
     */
    public function addMutator($mutator)
    {
        if (is_array($mutator) || $mutator instanceof MutatorInterface) {
            if (!array_key_exists('type', $mutator) || !$mutator['type']) {
                throw new Mutator\Exception\RuntimeException('Не задан тип мутатора.');
            }
            $mutator = $this->getMutatorPluginManager()->get($mutator['type'], $mutator);
        }
        $this->mutators[] = $mutator;
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
    abstract public function getRowSet();
}
