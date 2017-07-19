<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Condition;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Traversable;
use ArrayAccess;
use ReflectionClass;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ConditionFactory
 * @package Nnx\DataGrid\Condition
 */
class Factory implements FactoryInterface
{
    /**
     * Проверяет обязательные параметры для создания Condition
     * @param string $key
     * @param array $spec
     * @param string $errorText
     * @throws Exception\RuntimeException
     */
    protected function checkRequiredKey($key, array $spec, $errorText)
    {
        if (!array_key_exists($key, $spec) || !$spec[$key]) {
            throw new Exception\RuntimeException($errorText);
        }
    }

    /**
     * Валидируем пришедшие параметры
     * @param array $spec
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    protected function validate($spec)
    {
        if (!is_array($spec) && !$spec instanceof ArrayAccess) {
            throw new Exception\InvalidArgumentException('Для создания Condition в фабрику должен приходить массив или ArrayAccess');
        }

        $this->checkRequiredKey('type', $spec, 'Не задан тип Condition');
        $this->checkRequiredKey('key', $spec, 'Не задан ключ для Condition');
        $this->checkRequiredKey('value', $spec, 'Не задано значение для Condition');
    }

    /**
     * Создает критерий
     * @param array|Traversable $options
     * @return ConditionInterface
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->validate($options);
        if (!array_key_exists('criteria', $options) || !$options['criteria']) {
            $options['criteria'] = SimpleCondition::CRITERIA_TYPE_EQUAL;
        }
        if (!class_exists($options['type'])) {
            throw new Exception\RuntimeException(sprintf('Класс condition\'a %s не найден', $options['type']));
        }
        $reflection = new ReflectionClass($options['type']);
        if (!$reflection->isInstantiable()) {
            throw new Exception\RuntimeException(sprintf('Невозможно создать экземпляр condition\'a %s', $options['type']));
        }
        if (!$reflection->implementsInterface(ConditionInterface::class)) {
            throw new Exception\RuntimeException(sprintf('Condition должен реализовывать %s', ConditionInterface::class));
        }
        return $reflection->newInstanceArgs([$options['key'], $options['criteria'], $options['value']]);
    }


}
