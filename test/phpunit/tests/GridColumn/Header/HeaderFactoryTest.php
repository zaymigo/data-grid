<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace NNX\DataGrid\Test\PhpUnit\GridColumn\Header;

use NNX\DataGrid\Column\Header\Exception\NoValidSpecificationException;
use NNX\DataGrid\Column\Header\Factory;
use NNX\DataGrid\Column\Header\HeaderInterface;
use NNX\DataGrid\Test\PhpUnit\TestData\TestPath;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;
use Exception;

/**
 * Class HeaderFactoryTest
 * @package NNX\DataGrid\Test\PhpUnit\GridColumn\Header
 */
class HeaderFactoryTest extends AbstractControllerTestCase
{
    public function setUp()
    {
        $config = require TestPath::getApplicationConfigPath();
        $this->setApplicationConfig($config);
    }

    /**
     * Проверяет что создается корректный объект заголовка колонки фабрикой
     */
    public function testCreateHeader()
    {
        $factory = new Factory();
        $headerSpecification = [
            'data' => [
                'test1' => 'test1'
            ],
            'template' => 'view/grid/column/test',
            'options' => [
                'test2' => 'test2'
            ]
        ];
        $header = $factory->create($headerSpecification);

        self::assertInstanceOf(HeaderInterface::class, $header);
        self::assertEquals($headerSpecification['data'], $header->getData());
        self::assertEquals($headerSpecification['options'], $header->getOptions());
        self::assertEquals($headerSpecification['template'], $header->getTemplate());
    }


    public function testNoValidHeaderSpecification()
    {
        $factory = new Factory();
        try {
             $factory->create(null);
        } catch (Exception $e) {
            self::assertInstanceOf(NoValidSpecificationException::class, $e);
        }

    }
}