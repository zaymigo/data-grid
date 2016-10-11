<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Mutator;

use Nnx\DataGrid\Row;

/**
 * Class AddClassToCell
 * @package Nnx\DataGrid\Mutator
 * Добавляет указанным ячейкам грида указанные css-классы при рендере jqGrid
 */
class AddClassToCell extends AbstractMutator
{
    const ROW_KEY = 'additionalClass';

    /**
     * Имя ячейки строки в который добавляется класс
     * @var array
     */
    protected $cellName;

    /**
     * Класс добавляемый для ячейки строки
     * @var array
     */
    protected $className;

    /**
     * AddClassToCell constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);
        $this->setOptions($options);
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = [])
    {
        if (array_key_exists('cell', $options)) {
            $this->setCellName($options['cell']);
        }
        if (array_key_exists('class', $options)) {
            $this->setClassName($options['class']);
        }
    }

    /**
     * @param mixed $row
     * @return array|mixed|Row
     */
    public function change($row)
    {
        if (is_array($row) || $row instanceof Row) {
            foreach ($this->getCellName() as $cell) {
                $cellClass = isset($row[self::ROW_KEY][$cell]) ? $row[self::ROW_KEY][$cell] : [];
                $cellClass = array_merge($cellClass, $this->getClassName());
                $row[self::ROW_KEY][$cell]  = $cellClass;
            }
        }
        return $row;
    }

    /**
     * @return array
     */
    public function getCellName()
    {
        return $this->cellName;
    }

    /**
     * @param string|array $cellName
     */
    public function setCellName($cellName)
    {
        if (!is_array($cellName)) {
            $cellName = [$cellName];
        }
        $this->cellName = $cellName;
    }

    /**
     * @return array
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string|array $class
     */
    public function setClassName($class)
    {
        if (!is_array($class)) {
            $class = [$class];
        }
        $this->className = $class;
    }
}
