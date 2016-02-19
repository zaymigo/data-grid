<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Test\PhpUnit;
use MteGrid\Grid\GridPluginManager;
use MteGrid\Grid\Test\PhpUnit\TestData\TestPath;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;

/**
 * Class GridManagerFactoryTest 
 * @package MteGrid\Grid\Test\PhpUnit
 */
class GridManagerFactoryTest extends AbstractControllerTestCase
{

    /**
     * Проверяет создается корректно ли создается GridManager
     */
    public function testCreateGridManager()
    {
        $config = require TestPath::getApplicationConfigPath();
        $this->setApplicationConfig($config);
        $gridManager = $this->getApplicationServiceLocator()->get('GridManager');

        self::assertInstanceOf(GridPluginManager::class, $gridManager);
    }
}