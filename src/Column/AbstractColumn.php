<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Column;

use MteGrid\Grid\Column\Header\HeaderInterface;
use MteGrid\Grid\Mutator\MutatorInterface;
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
     * @var array|Traversable
     */
    protected $mutators;

    /**
     * @var array|Traversable
     */
    protected $invokableMutators;

    /**
     * @param string $key
     * @param array $options
     */
    protected function setProperty($key, &$options)
    {
        if (array_key_exists($key, $options)) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                $this->$setter($options[$key]);
                unset($options[$key]);
            }
        }
    }

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        foreach ($options as $key => $option) {
            $this->setProperty($key, $options);
        }
    }

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

    /**
     * @return array|Traversable
     */
    public function getMutators()
    {
        return $this->mutators;
    }

    /**
     * @param array|Traversable $mutators
     * @return $this
     */
    public function setMutators($mutators)
    {
        $this->mutators = $mutators;
        return $this;
    }

    /**
     * Добавляет мутьатор для ячеек данных
     * @param MutatorInterface $mutator
     * @return mixed
     */
    public function addMutator(MutatorInterface $mutator)
    {
        $this->mutators[] = $mutator;
        return $this;
    }

    /**
     * @return array|Traversable
     */
    public function getInvokableMutators()
    {
        return $this->invokableMutators;
    }

    /**
     * @param array|Traversable $invokableMutators
     * @return $this
     */
    public function setInvokableMutators($invokableMutators)
    {
        $this->invokableMutators = $invokableMutators;
        return $this;
    }
}
