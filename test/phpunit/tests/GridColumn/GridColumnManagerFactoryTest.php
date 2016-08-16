<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Test\PhpUnit\GridColumn;

use Nnx\DataGrid\Column\ColumnInterface;
use Nnx\DataGrid\Column\GridColumnPluginManager;
use Nnx\DataGrid\Test\PhpUnit\TestData\TestPath;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;

/**
 * Class GridColumnManagerFactory
 * @package Nnx\DataGrid\Test\PhpUnit\GridColumnManager
 */
class GridColumnManagerFactoryTest extends AbstractControllerTestCase
{

    /**
     *
     */
    public function testCreateGridColumnPluginManager()
    {
        $configPath = TestPath::getApplicationConfigPath();
        if (is_string($configPath) && file_exists($configPath)) {
            $config = require $configPath;
            $this->setApplicationConfig($config);

            /** @var GridColumnPluginManager $gridColumnManager */
            $gridColumnManager = $this->getApplicationServiceLocator()->get('GridColumnManager');
            self::assertInstanceOf(GridColumnPluginManager::class, $gridColumnManager);

            $textColumn = $gridColumnManager->get('text', [
                'type' => 'text',
                'name' => 'test'
            ]);
            self::assertInstanceOf(ColumnInterface::class, $textColumn);
        }
    }
}
