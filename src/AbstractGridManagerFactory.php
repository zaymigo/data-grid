<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid;


use Interop\Container\Exception\ContainerException;
use Nnx\DataGrid\Adapter\AdapterInterface;
use Nnx\DataGrid\NavigationBar\NavigationBarInterface;
use Nnx\DataGrid\Mutator\MutatorInterface;
use Nnx\DataGrid\Options\ModuleOptions;
use Psr\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use ArrayAccess;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\InitializableInterface;
use ZF\ContentNegotiation\Request;

/**
 * Class AbstractGridManager
 * @package Nnx\DataGrid
 */
class AbstractGridManagerFactory implements AbstractFactoryInterface
{
    /**
     * @var ContainerInterface
     */
    protected $serviceLocator;

    const CONFIG_KEY = 'grids';

    public function canCreate(\Interop\Container\ContainerInterface $container, $requestedName)
    {
        $res = false;
        if (strpos($requestedName, static::CONFIG_KEY . '.') === 0) {
            $res = true;
        }
        return $res;
    }

    /**
     * Создает экземпляр класса adapter'a и настраивает его.
     * @param array | ArrayAccess | AdapterInterface $adapterOptions
     * @param ServiceLocatorInterface $container
     * @return AdapterInterface|null
     * @throws Adapter\Exception\AdapterNotFoundException
     * @throws Adapter\Exception\InvalidArgumentException
     * @throws Adapter\Exception\InvalidOptionsException
     * @throws Adapter\Exception\RuntimeException
     * @throws Exception\RuntimeException
     */
    protected function createAdapter($adapterOptions, ServiceLocatorInterface $container)
    {
        $moduleOptions = $container->get('GridModuleOptions');
        if (is_array($adapterOptions) || $adapterOptions instanceof ArrayAccess) {
            /** @var Adapter\Factory $adapterFactory */
            $adapterFactory = $container->get(Adapter\Factory::class);
            if (!array_key_exists('doctrine_entity_manager', $adapterOptions)
                || !$adapterOptions['doctrine_entity_manager']
            ) {
                $adapterOptions['doctrine_entity_manager'] = $moduleOptions->getDoctrineEntityManager();
            }
            $adapter = $adapterFactory($container, '', $adapterOptions);
        } elseif (is_object($adapterOptions)) {
            /** @var Adapter\Factory $adapterFactory */
            $adapter = $adapterOptions;
            if (!$adapter instanceof AdapterInterface) {
                throw new Exception\RuntimeException(sprintf('Adapter должен реализовывать %s', AdapterInterface::class));
            }
        } else {
            throw new Exception\RuntimeException('Не задан EntityManager для грида.');
        }
        return $adapter;
    }

    /**
     * Возвращщает набор мутаторов
     * @param array $spec
     * @return array
     */
    protected function getMutators($spec)
    {
        $mutators = [];
        if (array_key_exists('mutators', $spec) && $spec['mutators']) {
            /** @var Mutator\GridMutatorPluginManager $mutatorFactory */
            $mutatorManager = $this->getServiceLocator()->get('GridMutatorManager');

            foreach ($spec['mutators'] as $mutator) {
                if (!$mutator instanceof MutatorInterface) {
                    if (!array_key_exists('type', $mutator) || !$mutator['type']) {
                        throw new Mutator\Exception\RuntimeException('Не передан type для создания мутатора.');
                    }
                    if ($mutatorManager->has($mutator['type'])) {
                        throw new Mutator\Exception\RuntimeException(
                            sprintf('Mutator %s не зарегистрирован в MutatorManager')
                        );
                    }
                    $mutator = $mutatorManager->get($mutator['type'], $mutator['options']);
                }
                $mutators[] = $mutator;
            }
        }
        return $mutators;
    }

    /**
     * @param \Interop\Container\ContainerInterface | ServiceManager $container
     * @param string $requestedName
     * @param array|null $options
     * @return AbstractGrid|GridInterface|SimpleGrid
     * @throws Exception\RuntimeException
     */
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->setServiceLocator($container);
        /** @var ServiceLocatorInterface $serviceManager */
        $serviceManager = $container;
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $serviceManager->get('GridModuleOptions');
        $gridsConfig = $moduleOptions->getGrids();
        /** @noinspection NotOptimalIfConditionsInspection */
        if ($gridsConfig === null || count($gridsConfig) === 0) {
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
            $adapter = $this->createAdapter($options['adapter'], $serviceManager);
            if (!empty($options['topNavigationBar'])) {
                $options['topNavigationBar'] = $this->createNavigationBar($options['topNavigationBar'], $serviceManager);
            }
            if (!empty($options['bottomNavigationBar'])) {
                $options['bottomNavigationBar'] = $this->createNavigationBar($options['bottomNavigationBar'], $serviceManager);
            }
            $options['adapter'] = $adapter;
        }
        $options['columnPluginManager'] = $serviceManager->get('GridColumnManager');
        $options['mutatorPluginManager'] = $serviceManager->get('GridMutatorManager');
        if (!$container->has($gridClass)) {
            $container->setFactory($gridClass, InvokableFactory::class);
        }
        //TODO подумать насчет кэширования shared
        /** @var GridInterface|AbstractGrid|SimpleGrid $grid */
        $grid = $container->build($gridClass, $options);


        if ($grid instanceof InitializableInterface) {
            $grid->init();
        }
        /** @var Request $request */
        $request = $serviceManager->get('request');
        if ($grid instanceof ColumHidebleProviderInterface
            && $request instanceof Request
        ) {
            $cookie = $request->getCookie();
            $name = !empty($gridConfig['options']['name']) ? $gridConfig['options']['name'] : $gridName;
            if (!empty($cookie['nnx']['grid'][$name])
                && is_string($cookie['nnx']['grid'][$name])
                && $userHideColumns = json_decode($cookie['nnx']['grid'][$name], true)
            ) {
                $grid->setUserHiddenColums($userHideColumns);
            }
        }
        return $grid;
    }





    /**
     * @param $navigationBarOptions
     * @param ServiceLocatorInterface $serviceManager
     * @return NavigationBarInterface|null
     * @throws NavigationBar\Exception\InvalidArgumentException
     * @throws NavigationBar\Exception\NavigationBarNotFoundException
     * @throws NavigationBar\Exception\RuntimeException
     */
    protected function createNavigationBar($navigationBarOptions, ServiceLocatorInterface $serviceManager)
    {
        /** @var NavigationBar\Factory $navigationBarFactory */
        $navigationBarFactory = $serviceManager->get(NavigationBar\Factory::class);
        $navigationBar = $navigationBarFactory->create($navigationBarOptions);
        return $navigationBar;
    }

    /**
     * @return ContainerInterface
     */
    public function getServiceLocator(): ContainerInterface
    {
        return $this->serviceLocator;
    }

    /**
     * @param ContainerInterface $serviceLocator
     * @return $this
     */
    public function setServiceLocator(ContainerInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }




}
