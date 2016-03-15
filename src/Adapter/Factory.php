<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Adapter;

use ArrayAccess;
use MteBase\Mapper\EntityManagerAwareInterface;
use MteGrid\Grid\Adapter\Exception\InvalidOptionsException;
use MteGrid\Grid\FactoryInterface;
use ReflectionClass;
use Traversable;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\InitializableInterface;

/**
 * Class Factory 
 * @package MteGrid\Grid\Adapter
 */
class Factory implements FactoryInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;


    /**
     * Создает экземпляр объекта
     * @param array | Traversable | string $spec
     * @return AdapterInterface|null
     * @throws Exception\AdapterNotFoundException
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     * @throws InvalidOptionsException
     */
    public function create($spec)
    {
        if (!is_array($spec) && $spec instanceof ArrayAccess) {
            throw new Exception\InvalidArgumentException(
                sprintf('В фабрику для создания адаптера таблицы должен приходить массив или %s', ArrayAccess::class)
            );
        }

        if (!array_key_exists('class', $spec) || !$spec['class']) {
            throw new Exception\RuntimeException('Секция адаптера для таблицы существует, но не задан класс адаптера');
        }
        $adapterClass =& $spec['class'];
        if (!class_exists($adapterClass)) {
            throw new Exception\AdapterNotFoundException(sprintf('Adapter %s не найден.', $adapterClass));
        }
        $reflectionAdapter = new ReflectionClass($adapterClass);
        if (!$reflectionAdapter->implementsInterface(AdapterInterface::class)) {
            throw new Exception\RuntimeException(
                sprintf('Adapter %s должен реализовывать %S', $spec, AdapterInterface::class)
            );
        }
        /** @var AdapterInterface | EntityManagerAwareInterface $adapter */
        $adapter = $reflectionAdapter->newInstance();
        if (array_key_exists('options', $spec) && $spec['options']) {
            $options =& $adapter['options'];
            if (!$options instanceof ArrayAccess && !is_array($options)) {
                throw new InvalidOptionsException(
                    sprintf('Опции для адаптера должны быть массивом или реализовывать %s', ArrayAccess::class)
                );
            }
            $adapter->setOptions($options);
        }

        if ($adapter instanceof EntityManagerAwareInterface
            && array_key_exists('doctrine_entity_manager', $spec)
            && $spec['doctrine_entity_manager']
        ) {
            $adapter->setEntityManager($this->getServiceLocator()->get($spec['doctrine_entity_manager']));
        }

        if ($adapter instanceof InitializableInterface) {
            $adapter->init();
        }
        return $adapter;
    }
}
