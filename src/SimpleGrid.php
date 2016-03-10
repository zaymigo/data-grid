<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid;

use Traversable;
use ArrayAccess;

/**
 * Class SimpleGrid 
 * @package MteGrid\Grid
 */
class SimpleGrid extends AbstractGrid
{
    /**
     * Данные в гриде
     * @var array
     */
    protected $rowSet;

    /**
     * Возвращает массив строк таблицы
     * @return array
     */
    public function getRowSet()
    {
        if(!$this->rowSet) {
            $data = $this->getAdapter()->getData();
            $this->buildRowSet($data);
        }

        return $this->rowSet;
    }

    /**
     * Создает из данных адаптера rowSet
     * @param array | ArrayAccess $data
     * @return $this
     * @throws Exception\RuntimeException
     */
    protected function buildRowSet($data)
    {
        if(!is_array($data) && $data instanceof Traversable) {
            throw new Exception\RuntimeException(
                sprintf('Данные должны быть массивом или %s', ArrayAccess::class)
            );
        }
        foreach ($data as $item) {
            $this->rowSet[] = new Row($item);
        }
        return $this;
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
}
