<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Ushkov Nikolai <ushkov@mte-telecom.ru>
 * Date: 18.04.16
 * Time: 17:34
 */

namespace Nnx\DataGrid\Button;

use Zend\ServiceManager\FactoryInterface;
use Nnx\DataGrid\Button\Exception\InvalidSpecificationException;
use Nnx\DataGrid\Button\Exception\InvalidButtonException;
use Nnx\DataGrid\Button\Exception\InvalidNameException;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Traversable;
use ReflectionClass;

/**
 * Фабрика кнопок навигационного бара
 * Class Factory
 * @package Nnx\DataGrid\Button
 */
class Factory implements MutableCreationOptionsInterface, FactoryInterface
{
    use GridButtonPluginManagerAwareTrait;
    use MutableCreationOptionsTrait;
    /**
     * @param array | Traversable $spec
     * @throws InvalidButtonException
     * @throws InvalidNameException
     * @throws InValidSpecificationException
     */
    protected function validate($spec)
    {
        if (!is_array($spec) && !$spec instanceof Traversable) {
            throw new InValidSpecificationException(
                sprintf('Передана некорректная спецификация для создания кнопки. Ожидается array или %s, прищел: %s',
                    Traversable::class,
                    gettype($spec)
                )
            );
        }
        if (!array_key_exists('type', $spec) || !$spec['type']) {
            throw new InvalidButtonException('Не передан тип создаваемой кнопки.');
        }
    }

    /**
     * Создает экземпляр класса фабрики
     * @param array|Traversable $spec
     * @throws InvalidButtonException
     * @throws InvalidNameException
     * @throws InValidSpecificationException
     * @return ButtonInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $spec = $this->getCreationOptions();
        $this->setButtonPluginManager($serviceLocator);
        $this->validate($spec);
        $className = __NAMESPACE__ . '\\' . ucfirst($spec['type']);
        $reflectionColumn = new ReflectionClass($className);
        if (!$reflectionColumn->isInstantiable()) {
            throw new Exception\RuntimeException(sprintf('Класс %s не найден', $className));
        }
        unset($spec['buttonPluginManager']);
        $button = $reflectionColumn->newInstance($spec);
        return $button;
    }
}
