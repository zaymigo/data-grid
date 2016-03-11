<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid;

use MteGrid\Grid\Adapter\AdapterInterface;
use MteGrid\Grid\Options\ModuleOptions;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ArrayAccess;

/**
 * Class AbstractGridManager 
 * @package MteGrid\Grid
 */
class AbstractGridManagerFactory implements AbstractFactoryInterface
{

    const CONFIG_KEY = 'grids';
    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $res = false;
        if (strpos($requestedName, 'grids.') === 0) {
            $res = true;
        }
        return $res;
    }

    /**
     * Create service with name
     *
     * @param GridPluginManager | ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     * @throws Exception\RuntimeException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        /** @var ServiceLocatorInterface $serviceManager */
        $serviceManager = $serviceLocator->getServiceLocator();
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $serviceManager->get('GridModuleOptions');
        $gridsConfig = $moduleOptions->getGrids();
        if ((is_array($gridsConfig) && 0 === count($gridsConfig)) || $gridsConfig === null) {
            throw new Exception\RuntimeException('В конфигурационном файле нет секции grids');
        }
        $gridName = substr($requestedName, strlen(self::CONFIG_KEY . '.'));

        if (!array_key_exists($gridName, $gridsConfig) || !$gridsConfig[$gridName]) {
            throw new Exception\RuntimeException(
                sprintf('Таблица с именем %s не найдена в конфиге гридов.', $gridName)
            );
        }
        $gridConfig =& $gridsConfig[$gridName];

        if (!array_key_exists('class', $gridConfig) || !$gridConfig['class']) {
            throw new Exception\RuntimeException('Необходимо задать класс таблицы в конфиге.');
        }
        $gridClass =& $gridConfig['class'];

        $options = [];
        if (array_key_exists('options', $gridConfig) && $gridConfig['options']) {
            if (!is_array($gridConfig['options']) && !$gridConfig['options'] instanceof ArrayAccess) {
                throw new Exception\RuntimeException(
                    sprintf('Опции в секции %s должны быть массивом или %s', $gridName, ArrayAccess::class)
                );
            }
            $options = $gridConfig['options'];
            $adapter = null;
            if (array_key_exists('adapter', $options) && $options['adapter'] && !is_object($options['adapter'])) {
                $adapterFactory = $serviceManager->get(Adapter\Factory::class);
                $adapter = $adapterFactory->create($options['adapter']);
            } elseif (is_object($options['adapter'])) {
                $adapter = $options['adapter'];
                if (!$adapter instanceof AdapterInterface) {
                    throw new Exception\RuntimeException(sprintf('Adapter должен реализовывать %s', AdapterInterface::class));
                }
            }
            $options['adapter'] = $adapter;
        }

        return $serviceLocator->get($gridClass, $options);
    }
}
