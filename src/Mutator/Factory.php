<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Mutator;

use Interop\Container\ContainerInterface;
use ReflectionClass;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Factory
 * @package Nnx\DataGrid\Mutator
 */
class Factory implements FactoryInterface
{
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
     * @throws Exception\RuntimeException
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->validate($options);

        $specOptions = [];
        if (array_key_exists('options', $options) && $options['options']) {
            $specOptions = $options['options'];
        }
        $className = __NAMESPACE__ . '\\' . ucfirst($options['type']);
        $reflectionClass = new ReflectionClass($className);
        if (!$reflectionClass->isInstantiable()) {
            throw new Exception\RuntimeException(
                sprintf('Невозможно создать экземпляр класса %s', $className)
            );
        }
        $mutator = $reflectionClass->newInstance($specOptions);
        return $mutator;
    }
}
