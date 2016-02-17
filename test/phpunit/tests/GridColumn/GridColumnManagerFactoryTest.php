<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Test\PhpUnit\GridColumn;


use MteGrid\Grid\Column\ColumnInterface;
use MteGrid\Grid\Column\GridColumnPluginManager;
use MteGrid\Grid\Test\PhpUnit\TestData\TestPath;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;

/**
 * Class GridColumnManagerFactory
 * @package MteGrid\Grid\Test\PhpUnit\GridColumnManager
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