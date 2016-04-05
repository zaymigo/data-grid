<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Mutator;

use ReflectionClass;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;

/**
 * Class Factory
 * @package Nnx\DataGrid\Mutator
 */
class Factory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * Конструктор класса
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setCreationOptions($options);
    }

    /**
     * Валидирует пришедшие данные для создания мутатора
     * @param array $spec
     * @throws Exception\RuntimeException
     */
    protected function validate($spec)
    {
        if (!array_key_exists('type', $spec) || !$spec['type']) {
            throw new Exception\RuntimeException('Для создания мутатора должен быть задан его тип');
        }
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface|GridMutatorPluginManager $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $spec = $this->getCreationOptions();
        $this->validate($spec);

        /** @var GridMutatorPluginManager $mutatorManager */
        $mutatorManager = $serviceLocator->getServiceLocator()->get('GridMutatorManager');
        $options = [];
        if (array_key_exists('options', $spec) && $spec['options']) {
            $options = $spec['options'];
        }
        $className = __NAMESPACE__ . '\\' . ucfirst($spec['type']);
        $reflectionClass = new ReflectionClass($className);
        if (!$reflectionClass->isInstantiable()) {
            throw new Exception\RuntimeException(
                sprintf('Невозможно создать экземпляр класса %s', $className)
            );
        }
        $mutator = $reflectionClass->newInstance($options);
        return $mutator;
    }
}
