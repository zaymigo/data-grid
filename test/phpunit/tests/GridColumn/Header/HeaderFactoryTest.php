<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Test\PhpUnit\GridColumn\Header;

use MteGrid\Grid\Column\Header\Exception\NoValidSpecificationException;
use MteGrid\Grid\Column\Header\Factory;
use MteGrid\Grid\Column\Header\HeaderInterface;
use MteGrid\Grid\Test\PhpUnit\TestData\TestPath;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;
use Exception;

/**
 * Class HeaderFactoryTest
 * @package MteGrid\Grid\Test\PhpUnit\GridColumn\Header
 */
class HeaderFactoryTest
    extends AbstractControllerTestCase
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