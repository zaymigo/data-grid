<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Ushkov Nikolai <ushkov@mte-telecom.ru>
 * Date: 19.04.16
 * Time: 12:02
 */

namespace Nnx\DataGrid\NavigationBar;

use Nnx\DataGrid\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\InitializableInterface;
use ArrayAccess;
use Traversable;
use ReflectionClass;

/**
 * Class Factory
 * @package Nnx\DataGrid\NavigationBar
 */
class Factory implements FactoryInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * Создает экземпляр объекта
     * @param array | Traversable | string $spec
     * @return NavigationBarInterface|null
     * @throws Exception\NavigationBarNotFoundException
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function create($spec)
    {
        if (!is_array($spec) && $spec instanceof ArrayAccess) {
            throw new Exception\InvalidArgumentException(
                sprintf('В фабрику для создания навигационного бара таблицы должен приходить массив или %s', ArrayAccess::class)
            );
        }
        if (array_key_exists('type', $spec) && $spec['type']) {
            $spec['class'] = __NAMESPACE__ . '\\' . ucfirst($spec['type']);
        }

        if (!array_key_exists('class', $spec) || !$spec['class']) {
            throw new Exception\RuntimeException('Секция навигационного бара существует, но не задан класс');
        }
        $navigationBarClass =& $spec['class'];
        if (!class_exists($navigationBarClass)) {
            throw new Exception\NavigationBarNotFoundException(sprintf('NavigationBar %s не найден.', $navigationBarClass));
        }
        $options = [];
        if (array_key_exists('options', $spec) && $spec['options']) {
            $options = $spec['options'];
        }
        $options['buttonPluginManager'] = $this->getServiceLocator()->get('GridButtonManager');
        $navigationBar = $this->createNavigationBar($navigationBarClass, $options);
        if ($navigationBar instanceof InitializableInterface) {
            $navigationBar->init();
        }
        return $navigationBar;
    }
    /**
     * @param string $navigationBarClass
     * @param array $spec
     * @return NavigationBarInterface
     * @throws Exception\RuntimeException
     */
    protected function createNavigationBar($navigationBarClass, $spec)
    {
        if ($this->getServiceLocator()->has($navigationBarClass)) {
            $navigationBar = $this->getServiceLocator()->get($navigationBarClass);
            if (!$navigationBar instanceof  NavigationBarInterface) {
                throw new Exception\RuntimeException(
                    sprintf('NavigationBar %s должен реализовывать %S', $spec, NavigationBarInterface::class)
                );
            }
        } else {
            $reflection = new ReflectionClass($navigationBarClass);
            if (!$reflection->implementsInterface(NavigationBarInterface::class)) {
                throw new Exception\RuntimeException(
                    sprintf('NavigationBar %s должен реализовывать %S', $spec, NavigationBarInterface::class)
                );
            }
            /** @var NavigationBarInterface $adapter */
            $navigationBar = $reflection->newInstance($spec);
        }
        return $navigationBar;
    }
}
