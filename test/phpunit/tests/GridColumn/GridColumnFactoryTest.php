<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Test\PhpUnit\GridColumn;


use MteGrid\Grid\Column\ColumnInterface;
use MteGrid\Grid\Column\Exception\InvalidColumnException;
use MteGrid\Grid\Column\Exception\InvalidSpecificationException;
use MteGrid\Grid\Column\Factory;
use MteGrid\Grid\Column\GridColumnPluginManager;
use MteGrid\Grid\Test\PhpUnit\TestData\TestPath;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;
use Exception;

/**
 * Class GridcolumnFactoryTest
 * @package MteGrid\Grid\Test\PhpUnit\GridColumn
 */
class GridColumnFactoryTest extends AbstractControllerTestCase
{

    /** @var GridColumnPluginManager  */
    protected $gridColumnManager;

    protected function setUp()
    {
        $config = require TestPath::getApplicationConfigPath();
        $this->setApplicationConfig($config);

        /** @var GridColumnPluginManager $gridColumnManager */
        $this->gridColumnManager = $this->getApplicationServiceLocator()->get('GridColumnManager');
        parent::setUp();
    }


    public function testCreateColumn()
    {
        $factory = new Factory();
        $factory->setColumnPluginManager($this->gridColumnManager);
        $columnSpec = [
            'type' => 'text',
            'header' => [],
            'name' => 'test',
            'options' => [],
            'attributes' => [],
            'template' => ''
        ];
        /** @var ColumnInterface $column */
        $column = $factory->create($columnSpec);

        self::assertEquals($columnSpec['name'],$column->getName());
        self::assertEquals($columnSpec['options'], $column->getOptions());
        self::assertEquals($columnSpec['attributes'], $column->getAttributes());
        self::assertEquals($columnSpec['template'], $column->getTemplate());
    }

    /**
     *
     */
    public function testEmptyTypeException()
    {
        $factory = new Factory();
        $factory->setColumnPluginManager($this->gridColumnManager);
        $columnSpec = [
            'header' => [],
            'name' => 'test',
            'options' => [],
            'attributes' => [],
            'template' => ''
        ];
        try {
            $factory->create($columnSpec);
        } catch (Exception $e) {
            self::assertInstanceOf(InvalidColumnException::class, $e);
        }
    }

    public function testEmptySpecificationException()
    {
        $factory = new Factory();
        $factory->setColumnPluginManager($this->gridColumnManager);
        try {
            $factory->create('');
        } catch(Exception $e) {
            self::assertInstanceOf(InvalidSpecificationException::class, $e);
        }
    }
}