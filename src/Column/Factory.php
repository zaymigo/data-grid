<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Column;

use MteGrid\Grid\Column\Exception\InvalidColumnException;
use MteGrid\Grid\Column\Exception\InvalidSpecificationException;
use Traversable;
use ArrayAccess;
use Zend\Hydrator\ClassMethods;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

/**
 * Class Factory
 * @package MteGrid\Grid\Column
 */
class Factory
{
    /**
     * @var GridColumnPluginManager
     */
    protected $columnPluginManager;

    /**
     * @return GridColumnPluginManager
     */
    public function getColumnPluginManager()
    {
        return $this->columnPluginManager;
    }

    /**
     * @param GridColumnPluginManager $columnPluginManager
     * @return $this
     */
    public function setColumnPluginManager($columnPluginManager)
    {
        $this->columnPluginManager = $columnPluginManager;
        return $this;
    }

    /**
     * @param array | Traversable$spec
     * @throws InvalidColumnException
     * @throws InvalidSpecificationException
     */
    protected function validate($spec)
    {
        if(!is_array($spec) && !$spec instanceof Traversable && !$spec instanceof ArrayAccess) {
            throw new InvalidSpecificationException(sprintf('Не задан '));
        }
        if(!array_key_exists('type', $spec) || !$spec['type']) {
            throw new InvalidColumnException(sprintf('Не передан тип создаваемого столбца.'));
        }

    }

    /**
     * Метод фабрики создающий непосредственно колонку
     * @param array | Traversable $spec
     * @return ColumnInterface
     * @throws InvalidColumnException
     * @throws InvalidSpecificationException
     * @throws ServiceNotFoundException
     * @throws ServiceNotCreatedException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \Zend\Hydrator\Exception\BadMethodCallException
     */
    public function create($spec)
    {
        $this->validate($spec);
        /** @var ColumnInterface $column */
        $column = $this->getColumnPluginManager()->get($spec['type']);
        $classMethods = new ClassMethods();
        $column = $classMethods->hydrate($spec, $column);

//        $headerFactory = new Header\Factory();
//        $headerFactory->create($spec['header']);
        return $column;
    }
}
