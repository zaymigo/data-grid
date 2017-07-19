<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Adapter;

use ArrayAccess;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Nnx\DataGrid\Adapter\Exception\InvalidOptionsException;
use ReflectionClass;
use Traversable;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Stdlib\InitializableInterface;

/**
 * Class Factory
 * @package Nnx\DataGrid\Adapter
 */
class Factory implements FactoryInterface
{

    /**
     * @param string $adapterClass
     * @param array $spec
     * @return AdapterInterface|EntityManagerAwareInterface
     * @throws Exception\RuntimeException
     */
    protected function createAdapter(ContainerInterface $container, $adapterClass, $spec)
    {
        if ($container->has($adapterClass)) {
            $adapter = $container->get($adapterClass);
        } else {
            $reflectionAdapter = new ReflectionClass($adapterClass);
            if (!$reflectionAdapter->implementsInterface(AdapterInterface::class)) {
                throw new Exception\RuntimeException(
                    sprintf('Adapter %s должен реализовывать %S', $spec, AdapterInterface::class)
                );
            }
            /** @var AdapterInterface | EntityManagerAwareInterface $adapter */
            $adapter = $reflectionAdapter->newInstance();
        }
        return $adapter;
    }

    /**
     * Создает экземпляр объекта
     * @param array | Traversable | string $options
     * @return AdapterInterface|null
     * @throws Exception\AdapterNotFoundException
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     * @throws InvalidOptionsException
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (!is_array($options) && !($options instanceof ArrayAccess)) {
            throw new Exception\InvalidArgumentException(
                sprintf('В фабрику для создания адаптера таблицы должен приходить массив или %s', ArrayAccess::class)
            );
        }

        if (!array_key_exists('class', $options) || !$options['class']) {
            throw new Exception\RuntimeException('Секция адаптера для таблицы существует, но не задан класс адаптера');
        }
        $adapterClass =& $options['class'];
        if (!class_exists($adapterClass)) {
            throw new Exception\AdapterNotFoundException(sprintf('Adapter %s не найден.', $adapterClass));
        }

        $adapter = $this->createAdapter($container, $adapterClass, $options);
        if (array_key_exists('options', $options) && $options['options']) {
            $specOptions =& $options['options'];
            if (!$specOptions instanceof ArrayAccess && !is_array($specOptions)) {
                throw new InvalidOptionsException(
                    sprintf('Опции для адаптера должны быть массивом или реализовывать %s', ArrayAccess::class)
                );
            }
            $adapter->setOptions($specOptions);
        }

        if ($adapter instanceof EntityManagerAwareInterface
            && array_key_exists('doctrine_entity_manager', $options)
            && $options['doctrine_entity_manager']
        ) {
            $adapter->setEntityManager($container->get($options['doctrine_entity_manager']));
        }

        if ($adapter instanceof InitializableInterface) {
            $adapter->init();
        }
        return $adapter;
    }


}
