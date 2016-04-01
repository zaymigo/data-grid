<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace NNX\DataGrid\Test\PhpUnit\GridColumn;


use NNX\DataGrid\Column\ColumnInterface;
use NNX\DataGrid\Column\GridColumnPluginManager;
use NNX\DataGrid\Test\PhpUnit\TestData\TestPath;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;

/**
 * Class GridColumnManagerFactory
 * @package NNX\DataGrid\Test\PhpUnit\GridColumnManager
 */
class GridColumnManagerFactoryTest extends AbstractControllerTestCase
{

    /**
     *
     */
    public function testCreateGridColumnPluginManager()
    {
        $configPath = TestPath::getApplicationConfigPath();
        if(is_string($configPath) && file_exists($configPath)) {
            $config = require $configPath;
            $this->setApplicationConfig($config);

            /** @var GridColumnPluginManager $gridColumnManager */
            $gridColumnManager = $this->getApplicationServiceLocator()->get('GridColumnManager');
            self::assertInstanceOf(GridColumnPluginManager::class, $gridColumnManager);

            $textColumn = $gridColumnManager->get('text');
            self::assertInstanceOf(ColumnInterface::class, $textColumn);
        }
    }
}