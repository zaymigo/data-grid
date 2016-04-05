<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Test\PhpUnit\GridColumn\Header;

use Nnx\DataGrid\Column\Header\Exception\NoValidSpecificationException;
use Nnx\DataGrid\Column\Header\Factory;
use Nnx\DataGrid\Column\Header\HeaderInterface;
use Nnx\DataGrid\Test\PhpUnit\TestData\TestPath;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;
use Exception;

/**
 * Class HeaderFactoryTest
 * @package Nnx\DataGrid\Test\PhpUnit\GridColumn\Header
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