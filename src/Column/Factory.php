<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Column;

use MteGrid\Grid\Column\Exception\InvalidColumnException;
use MteGrid\Grid\Column\Exception\InvalidNameException;
use MteGrid\Grid\Column\Exception\InvalidSpecificationException;
use MteGrid\Grid\FactoryInterface;
use Traversable;
use Zend\Http\Header\HeaderInterface;
use Zend\Hydrator\ClassMethods;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

/**
 * Class Factory
 * @package MteGrid\Grid\Column
 */
final class Factory implements FactoryInterface
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
     * @param array | Traversable $spec
     * @throws InvalidColumnException
     * @throws InvalidNameException
     * @throws InvalidSpecificationException
     */
    protected function validate($spec)
    {
        if (!is_array($spec) && !$spec instanceof Traversable) {
            throw new InvalidSpecificationException(
                sprintf('Передана некорректная спецификация для создания колонки. Ожидается array или %s, прищел: %s',
                    Traversable::class,
                    gettype($spec)
                    )
            );
        }
        if (!array_key_exists('type', $spec) || !$spec['type']) {
            throw new InvalidColumnException('Не передан тип создаваемого столбца.');
        }
        if (!array_key_exists('name', $spec) || !$spec['name']) {
            throw new InvalidNameException('Не задано имя для колонки.');
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
     * @throws \MteGrid\Grid\Column\Exception\InvalidNameException
     * @throws \MteGrid\Grid\Column\Header\Exception\NoValidSpecificationException
     * @throws \MteGrid\Grid\Column\Header\Exception\NoValidTemplateException
     */
    public function create($spec)
    {
        $this->validate($spec);
        /** @var ColumnInterface $column */
        $column = $this->getColumnPluginManager()->get($spec['type']);
        /** @var ClassMethods $classMethods */
        $classMethods =  $this->getColumnPluginManager()
            ->getServiceLocator()
            ->get('HydratorManager')
            ->get('ClassMethods');
        $column = $classMethods->hydrate($spec, $column);

        if (array_key_exists('header', $spec)
            && $spec['header']
            && !$spec['header'] instanceof HeaderInterface) {
            $headerFactory = new Header\Factory();
            $header = $headerFactory->create($spec['header']);
            $column->setHeader($header);
        }

        return $column;
    }
}
