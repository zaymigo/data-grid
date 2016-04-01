<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace NNX\DataGrid\Test\PhpUnit\GridColumn;


use NNX\DataGrid\Column\ColumnInterface;
use NNX\DataGrid\Column\Exception\InvalidColumnException;
use NNX\DataGrid\Column\Exception\InvalidNameException;
use NNX\DataGrid\Column\Exception\InvalidSpecificationException;
use NNX\DataGrid\Column\Factory;
use NNX\DataGrid\Column\GridColumnPluginManager;
use NNX\DataGrid\Column\Header\HeaderInterface;
use NNX\DataGrid\Test\PhpUnit\TestData\TestPath;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;
use Exception;

/**
 * Class GridColumnFactoryTest
 * @package NNX\DataGrid\Test\PhpUnit\GridColumn
 */
class GridColumnFactoryTest extends AbstractControllerTestCase
{
    /**
     * @var GridColumnPluginManager
     */
    protected $gridColumnManager;

    /**
     * @var Factory
     */
    protected $factory;

    /**
     *
     */
    protected function setUp()
    {
        $config = require TestPath::getApplicationConfigPath();
        $this->setApplicationConfig($config);

        /** @var GridColumnPluginManager|array $gridColumnManager */
        $this->gridColumnManager = $this->getApplicationServiceLocator()->get('GridColumnManager');
        parent::setUp();
    }

    /**
     * возвращает фабрику колонок таблицы
     * @return Factory
     */
    protected function getFactory()
    {
        if(!$this->factory) {
            $factory = new Factory();
            $factory->setColumnPluginManager($this->gridColumnManager);
            $this->factory = $factory;
        }
        return $this->factory;
    }


    /**
     *
     */
    public function testCreateColumn()
    {
        $factory = $this->getFactory();
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
     * проверяет что возвращается исключение в случае если не задан тип
     */
    public function testEmptyTypeException()
    {
        $factory = $this->getFactory();
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

    /**
     * Проверяет что фабрика возвращает исключение в случае если приходит
     * не массив
     */
    public function testEmptySpecificationException()
    {
        $factory = $this->getFactory();
        try {
            $factory->create('');
        } catch(Exception $e) {
            self::assertInstanceOf(InvalidSpecificationException::class, $e);
        }
    }

    /**
     * Проверяет что фабрика возвращает исключение в случае если приходит
     * не массив
     */
    public function testEmptyNameException()
    {
        $factory = $this->getFactory();
        $columnSpec = [
            'type' => 'text',
            'header' => [],
            'options' => [],
            'attributes' => [],
            'template' => ''
        ];
        try {
            $factory->create($columnSpec);
        } catch(Exception $e) {
            self::assertInstanceOf(InvalidNameException::class, $e);
        }
    }

    /**
     * Проверка что при создании колонки создается экземляр класса заголовка
     */
    public function testCreateColumnWithHeader()
    {
        $factory = $this->getFactory();
        $columnSpec = [
            'type' => 'text',
            'name' => 'test',
            'header' => [
                'template' => 'var',
                'data' => ['key' => 'val']
            ]
        ];
        $column = $factory->create($columnSpec);

        self::assertInstanceOf(HeaderInterface::class,$column->getHeader());
        self::assertEquals($columnSpec['header']['data'], $column->getHeader()->getData());
        self::assertEquals($columnSpec['header']['template'], $column->getHeader()->getTemplate());
    }
}