<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Test\PhpUnit;

use Nnx\DataGrid\Adapter\AdapterInterface;
use Nnx\DataGrid\GridInterface;
use Nnx\DataGrid\GridPluginManager;
use Nnx\DataGrid\Options\ModuleOptions;
use Nnx\DataGrid\Row;
use Nnx\DataGrid\SimpleGrid;
use Nnx\DataGrid\Test\PhpUnit\TestData\TestPath;
use Zend\ModuleManager\ModuleEvent;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;
use Exception;
use Nnx\DataGrid\Exception\RuntimeException;


/**
 * Class GridManagerFactoryTest
 * @package Nnx\DataGrid\Test\PhpUnit
 */
class GridManagerFactoryTest extends AbstractControllerTestCase
{
    public function setUp()
    {
        $config = require TestPath::getApplicationConfigPath();
        $this->setApplicationConfig($config);
        $this->getApplication()->getEventManager()->attach(ModuleEvent::EVENT_MERGE_CONFIG, function(ModuleEvent $e) {
            $configListener = $e->getConfigListener();
            $config         = $configListener->getMergedConfig(false);

            // Modify the configuration; here, we'll remove a specific key:
            if (isset($config['grids']['SimpleGrid']['options'])) {
                unset($config['grids']['SimpleGrid']['options']);
            }

            // Pass the changed configuration back to the listener:
            $configListener->setMergedConfig($config);
        });
    }

    /**
     * Проверяет создается корректно ли создается GridManager
     */
    public function testCreateGridManager()
    {
        $gridManager = $this->getApplicationServiceLocator()->get('GridManager');

        self::assertInstanceOf(GridPluginManager::class, $gridManager);
    }

    /**
     * Проверяет создание грида
     */
    public function testCreateGrid()
    {
        /** @var GridPluginManager $gridManager */
        $gridManager = $this->getApplicationServiceLocator()->get('GridManager');
        /** @var GridInterface $grid */
        $grid = $gridManager->get('grids.SimpleGrid');
        self::assertInstanceOf(SimpleGrid::class, $grid);
        self::assertInstanceOf(AdapterInterface::class, $grid->getAdapter());
    }

    public function testCreateGridInvalidConfig()
    {
        /** @var ModuleOptions $options */
        $options = $this->getApplication()->getServiceManager()->get('GridModuleOptions');
        $gridsConfig = $options->getGrids();
        $options->setGrids([]);
        /** @var GridPluginManager $gridManager */
        $gridManager = $this->getApplicationServiceLocator()->get('GridManager');
        try {
            /** @var GridInterface $grid */
            $gridManager->get('grids.SimpleGrid');
        } catch (\RuntimeException $e) {
            self::assertInstanceOf(\Zend\ServiceManager\Exception\ServiceNotCreatedException::class, $e);
        }
        $options->setGrids($gridsConfig);

    }

    public function testExceptionNotFoundGrid()
    {
        /** @var GridPluginManager $gridManager */
        $gridManager = $this->getApplicationServiceLocator()->get('GridManager');
        try {
            /** @var GridInterface $grid */
            $gridManager->get('grids.Test');
        } catch (\RuntimeException $e) {
            self::assertInstanceOf(\Zend\ServiceManager\Exception\ServiceNotCreatedException::class, $e);
        }
    }

    public function testGridPluginManagerValidate()
    {
        try {
            /** @var GridPluginManager $gridManager */
            $gridManager = $this->getApplicationServiceLocator()->get('GridManager');
            /** @var GridInterface $grid */
            $gridManager->get(Row::class, ['test' => 'test']);
        } catch (\Exception $e) {
            self::assertInstanceOf(RuntimeException::class, $e);
        }
    }


}