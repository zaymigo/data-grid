<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid;

use Nnx\DataGrid\Column\GridColumnProviderInterface;
use Nnx\DataGrid\Mutator\GridMutatorProviderInterface;
use Nnx\DataGrid\Options\ModuleOptions;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Listener\ServiceListenerInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Module
 * @package Nnx\DataGrid
 */
class Module
    implements ServiceLocatorAwareInterface,
    ConfigProviderInterface,
    AutoloaderProviderInterface,
    BootstrapListenerInterface,
    ServiceProviderInterface,
    InitProviderInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * Имя секции в конфигах приложения, содержащей настройки модуля
     *
     * @var string
     */
    const CONFIG_KEY = 'mteGrid';

    /**
     * Имя модуля
     *
     * @var string
     */
    const MODULE_NAME = __NAMESPACE__;

    /**
     * Получаем конфигурацию модуля
     * @return array
     */
    public function getConfig()
    {
        return require __DIR__ . '/config/module.config.php';
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        $config = [];
        $autoloadFile = __DIR__ . '/autoload_classmap.php';
        if (file_exists($autoloadFile)) {
            $config['Zend\Loader\ClassMapAutoloader'] = [
                $autoloadFile
            ];
        }
        $config['Zend\Loader\StandardAutoloader'] = [
            'namespaces' => [
                __NAMESPACE__ => __DIR__ . '/src'
            ]
        ];
        return $config;
    }

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var MvcEvent $e */
        $this->setServiceLocator($e->getApplication()->getServiceManager());
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return [];
    }

    /**
     * Initialize workflow
     *
     * @param  ModuleManagerInterface $manager
     * @throws Exception\RuntimeException
     */
    public function init(ModuleManagerInterface $manager)
    {
        if (!$manager instanceof ModuleManager) {
            $errMsg =sprintf('Менеджер модулей должен реализовывать %s', ModuleManager::class);
            throw new Exception\RuntimeException($errMsg);
        }
        /** @var ModuleManager $manager */

        /** @var ServiceLocatorInterface $sm */
        $sm = $manager->getEvent()->getParam('ServiceManager');

        /** @var ServiceListenerInterface $serviceListener */
        $serviceListener = $sm->get('ServiceListener');
        $serviceListener->addServiceManager(
            'GridManager',
            'grid_manager',
            GridProviderInterface::class,
            'getGridConfig'
        );

        $serviceListener->addServiceManager(
            'GridColumnManager',
            'grid_columns',
            GridColumnProviderInterface::class,
            'getGridColumnConfig'
        );
        $serviceListener->addServiceManager(
            'GridMutatorManager',
            'grid_mutators',
            GridMutatorProviderInterface::class,
            'getGridMutatorConfig'
        );
    }

    /**
     * Возвращает объект с настройками модуля
     *
     * @return ModuleOptions
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function getModuleOptions()
    {
        /** @var ModuleOptions $moduleOptions */
        return $this->getServiceLocator()->get('GridModuleOptions');
    }
}