<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Condition;

use MteGrid\Grid\FactoryInterface;
use Traversable;
use ArrayAccess;
use ReflectionClass;

/**
 * Class ConditionFactory
 * @package MteGrid\Grid\Condition
 */
class Factory implements FactoryInterface
{

    /**
     * @param array|Traversable $spec
     * @return ConditionInterface
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function create($spec)
    {
        if (!is_array($spec) && !$spec instanceof ArrayAccess) {
            throw new Exception\InvalidArgumentException('Для создания Condition в фабрику должен приходить массив или ArrayAccess');
        }

        if (!array_key_exists('type', $spec) || !$spec['type']) {
            throw new Exception\RuntimeException('Не задан тип Condition');
        }

        if (!array_key_exists('key', $spec) || !$spec['key']) {
            throw new Exception\RuntimeException('Не задан ключ для Condition');
        }

        if (!array_key_exists('value', $spec)) {
            throw new Exception\RuntimeException('Не задано значение для Condition');
        }

        if (!array_key_exists('criteria', $spec) || !$spec['criteria']) {
            $spec['criteria'] = SimpleCondition::CRITERIA_TYPE_EQUAL;
        }

        if (!class_exists($spec['type'])) {
            throw new Exception\RuntimeException(sprintf('Класс condition\'a %s не найден', $spec['type']));
        }
        $reflection = new ReflectionClass($spec['type']);
        if (!$reflection->isInstantiable()) {
            throw new Exception\RuntimeException(sprintf('Невозможно создать экземпляр condition\'a %s', $spec['type']));
        }
        if (!$reflection->implementsInterface(ConditionInterface::class)) {
            throw new Exception\RuntimeException(sprintf('Condition должен реализовывать %s', ConditionInterface::class));
        }
        return $reflection->newInstanceArgs([$spec['key'], $spec['criteria'], $spec['value']]);
    }
}
