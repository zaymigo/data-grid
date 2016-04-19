<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid;

use Nnx\DataGrid\Column\ColumnInterface;
use Nnx\DataGrid\Mutator\MutatorInterface;
use Traversable;
use ArrayAccess;

/**
 * Class SimpleGrid
 * @package Nnx\DataGrid
 */
class SimpleGrid extends AbstractGrid implements ColumHidebleProviderInterface
{
    /**
     * Коллекция колонок таблицы скрытых/показаннах пользователем
     * @var array | Traversable
     */
    protected $userHiddenColums;

    /**
     * Флаг указывающий на изменеи списка колонок используется для проверки необходимости переустановки скрытия колонок пользователем
     * @var bool
     */
    protected $columsChanged = false;
    /**
     * Данные в гриде
     * @var array
     */
    protected $rowSet = [];

    /**
     * Возвращает массив строк таблицы
     * @return array
     */
    public function getRowSet()
    {
        if (count($this->rowSet) === 0) {
            $data = $this->getAdapter()->getData();
            $this->buildRowSet($data);
        }

        return $this->rowSet;
    }

    /**
     * Добавление колонок в таблицу
     * @param array | Traversable $columns
     * @return $this
     */
    public function setColumns($columns)
    {
        $this->setColumsChanged(true);
        return parent::setColumns($columns);
    }
    /**
     * Добавление колонки в таблицу
     * @param ColumnInterface|array|ArrayAccess $column
     * @return $this
     * @throws Column\Exception\InvalidColumnException
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function add($column)
    {
        $this->setColumsChanged(true);
        return parent::add($column);
    }


    /**
     * Создает из данных адаптера rowSet
     * @param array | ArrayAccess $data
     * @return $this
     * @throws Exception\RuntimeException
     */
    protected function buildRowSet($data)
    {
        if (!is_array($data) && $data instanceof Traversable) {
            throw new Exception\RuntimeException(
                sprintf('Данные должны быть массивом или %s', ArrayAccess::class)
            );
        }
        $columns = $this->getColumns();

        foreach ($data as $item) {
            $mutators = $this->getMutators();
            $item = $this->mutate($mutators, $item);
            /** @var ColumnInterface $column */
            foreach ($columns as $column) {
                $columnName = $column->getName();
                $mutators = $column->getMutators();
                if (array_key_exists($columnName, $item)) {
                    $item = $this->mutate($mutators, $item, $columnName);
                }
            }
            $this->rowSet[] = new Row($item);
        }
        return $this;
    }

    /**
     * Метод непосредственно осуществляет мутацию данных
     * @param array $mutators
     * @param array | Row $row
     * @param string $name
     * @return mixed
     */
    protected function mutate(array $mutators, $row, $name = null)
    {
        /** @var MutatorInterface $mutator */
        foreach ($mutators as $mutator) {
            if ($mutator instanceof MutatorInterface) {
                $mutator->setRowData($row);
                if ($mutator->validate()) {
                    if ($name) {
                        $row[$name] = $mutator->change($row[$name]);
                    } else {
                        $row = $mutator->change($row);
                    }
                }
            }
        }
        return $row;
    }

    /**
     * @param array $rowSet
     * @return $this
     */
    public function setRowSet($rowSet)
    {
        $this->rowSet = $rowSet;
        return $this;
    }

    /**
     * Функция инициализации колонок
     * @return void
     */
    public function init()
    {
    }
    /**
     * Возвращает коллекцию колонок
     * @return array | Traversable
     */
    public function getColumns()
    {
        $colums = parent::getColumns();
        if ($this->getUserHiddenColums() && $this->isColumsChanged()) {
            $newColums = [];
            $addedColums = [];
            foreach ($this->getUserHiddenColums() as $i => $userColum) {
                /** @var ColumnInterface $colum */
                if (!empty($userColum['n']) && $colum = $this->get($userColum['n'])) {
                    $addedColums[$userColum['n']] = true;
                    $colum->setOrder($i);
                    if (!empty($userColum['h'])) {
                        $colum->setAttribute('hidden', (bool)$userColum['h']);
                    }
                    $newColums[] = $colum;
                }
            }
            $i++;
            /** @var ColumnInterface $colum */
            foreach ($colums as $colum) {
                if (empty($addedColums[$colum->getName()])) {
                    $colum->setOrder($i);
                    $newColums[] = $colum;
                }
            }
            return $newColums;
        }
        return $colums;
    }


    /**
     * получить коллекцию колонок таблицы скрытых/показаннах пользователем
     * @return array|Traversable
     */
    public function getUserHiddenColums()
    {
        return $this->userHiddenColums;
    }

    /**
     * установить коллекцию колонок таблицы скрытых/показаннах пользователем
     * @param array|Traversable $userHiddenColums
     * @return $this
     */
    public function setUserHiddenColums($userHiddenColums)
    {
        $this->setColumsChanged(true);
        $this->userHiddenColums = $userHiddenColums;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isColumsChanged()
    {
        return $this->columsChanged;
    }

    /**
     * @param boolean $columsChanged
     * @return $this
     */
    public function setColumsChanged($columsChanged)
    {
        $this->columsChanged = $columsChanged;
        return $this;
    }
}
