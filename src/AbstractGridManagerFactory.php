<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid;

use Nnx\DataGrid\Adapter\AdapterInterface;
use Nnx\DataGrid\NavigationBar\NavigationBarInterface;
use Nnx\DataGrid\Mutator\MutatorInterface;
use Nnx\DataGrid\Options\ModuleOptions;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use ArrayAccess;
use Zend\Stdlib\InitializableInterface;

/**
 * Class AbstractGridManager
 * @package Nnx\DataGrid
 */
class AbstractGridManagerFactory implements AbstractFactoryInterface
{
    use ServiceLocatorAwareTrait;

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
        if (strpos($requestedName, static::CONFIG_KEY . '.') === 0) {
            $res = true;
        }
        return $res;
    }

    /**
     * Создает экземпляр класса adapter'a и настраивает его.
     * @param array | ArrayAccess | AdapterInterface $adapterOptions
     * @param ServiceLocatorInterface $serviceManager
     * @return AdapterInterface|null
     * @throws Adapter\Exception\AdapterNotFoundException
     * @throws Adapter\Exception\InvalidArgumentException
     * @throws Adapter\Exception\InvalidOptionsException
     * @throws Adapter\Exception\RuntimeException
     * @throws Exception\RuntimeException
     */
    protected function createAdapter($adapterOptions, ServiceLocatorInterface $serviceManager)
    {
        $moduleOptions = $serviceManager->get('GridModuleOptions');
        if (is_array($adapterOptions) || $adapterOptions instanceof ArrayAccess) {
            /** @var Adapter\Factory $adapterFactory */
            $adapterFactory = $serviceManager->get(Adapter\Factory::class);
            if (!array_key_exists('doctrine_entity_manager', $adapterOptions)
                || $adapterOptions['doctrine_entity_manager']
            ) {
                $adapterOptions['doctrine_entity_manager'] = $moduleOptions->getDoctrineEntityManager();
            }
            $adapter = $adapterFactory->create($adapterOptions);
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
        $this->setServiceLocator($serviceLocator);
        /** @var ServiceLocatorInterface $serviceManager */
        $serviceManager = $serviceLocator->getServiceLocator();
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
        /** @var GridInterface|AbstractGrid $grid */
        $grid = $serviceLocator->get($gridClass, $options);
        if ($grid instanceof InitializableInterface) {
            $grid->init();
        }
        if ($grid instanceof ColumHidebleProviderInterface) {
            /** @var \ZF\ContentNegotiation\Request $request */
            $request = $serviceManager->get('request');
            $cookie = $request->getCookie();
            $name = !empty($gridConfig['options']['name'])? $gridConfig['options']['name'] : $gridName;
            if (!empty($cookie['nnx']['grid'][$name])
                && is_string($cookie['nnx']['grid'][$name])
                && $userHideColums = json_decode($cookie['nnx']['grid'][$name], true)) {
                $grid->setUserHiddenColums($userHideColums);
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
}
