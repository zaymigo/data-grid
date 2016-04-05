<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Test\PhpUnit\GridCondition;

use Nnx\DataGrid\Condition\ConditionInterface;
use Nnx\DataGrid\Condition\SimpleCondition;
use Nnx\DataGrid\Test\PhpUnit\TestData\TestPath;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Nnx\DataGrid\Condition\Factory;
use Nnx\DataGrid\Condition\DB;
use Nnx\DataGrid\Condition\Exception;

/**
 * Class FactoryTest
 * @package Nnx\DataGrid\Test\PhpUnit\GridCondition
 */
class FactoryTest extends AbstractHttpControllerTestCase
{
    /**
     * @var Factory
     */
    protected $factory;

    protected function setUp()
    {
        $config = require TestPath::getApplicationConfigPath();
        $this->setApplicationConfig($config);

        $this->factory = new Factory();
        parent::setUp();
    }

    /**
     * @throws \Nnx\DataGrid\Condition\Exception\InvalidArgumentException
     * @throws \Nnx\DataGrid\Condition\Exception\RuntimeException
     */
    public function testCreateCondition()
    {
        $conditionSpec = [
            'type' => DB::class,
            'key' => 'test',
            'criteria' => DB::CRITERIA_TYPE_GREATER,
            'value' => 1
        ];
        $condition = $this->factory->create($conditionSpec);

        self::assertInstanceOf(DB::class, $condition);
        self::assertEquals($conditionSpec['key'], $condition->getKey());
        self::assertEquals($conditionSpec['value'], $condition->getValue());
        self::assertEquals($conditionSpec['criteria'], $condition->getCriteria());
    }

    /**
     * Проверяет корректность возвращаемого exception в случае пустого значения спецификации condition
     */
    public function testCreateConditionInvalidSpec()
    {
        $conditionSpec = '';
        try {
            $this->factory->create($conditionSpec);
        } catch(\Exception $e) {
            self::assertInstanceOf(Exception\InvalidArgumentException::class, $e);
        }
    }

    /**
     * Проверяет корректность возвращаемого exception в случае пустого ключа
     */
    public function testCreateConditionWithInvalidKey()
    {
        $conditionSpec = [
            'type' => DB::class,
            'key' => '',
            'criteria' => DB::CRITERIA_TYPE_GREATER,
            'value' => 1
        ];

        try {
            $this->factory->create($conditionSpec);
        } catch(\Exception $e) {
            self::assertInstanceOf(Exception\RuntimeException::class, $e);
            self::assertEquals('Не задан ключ для Condition', $e->getMessage());
        }
    }

    public function testCreateConditionWithNullType()
    {
        $conditionSpec = [
            'type' => null,
            'key' => 'test',
            'criteria' => DB::CRITERIA_TYPE_GREATER,
            'value' => 1
        ];

        try {
            $this->factory->create($conditionSpec);
        } catch(\Exception $e) {
            self::assertInstanceOf(Exception\RuntimeException::class, $e);
            self::assertEquals('Не задан тип Condition', $e->getMessage());
        }
    }

    public function testCreateConditionWithInvalidType()
    {
        $conditionSpec = [
            'type' => 'ddsds',
            'key' => 'test',
            'criteria' => DB::CRITERIA_TYPE_GREATER,
            'value' => 1
        ];

        try {
            $this->factory->create($conditionSpec);
        } catch(\Exception $e) {
            self::assertInstanceOf(Exception\RuntimeException::class, $e);
            self::assertEquals(sprintf('Класс condition\'a %s не найден', $conditionSpec['type']), $e->getMessage());
        }
    }

    public function testCreateConditionWithEmptyCriteria()
    {
        $conditionSpec = [
            'type' => SimpleCondition::class,
            'key' => 'test',
            'value' => 1
        ];
        $condition = $this->factory->create($conditionSpec);
        self::assertEquals(SimpleCondition::CRITERIA_TYPE_EQUAL, $condition->getCriteria());
    }

    public function testConditionWithEmptyValue()
    {
        $conditionSpec = [
            'type' => SimpleCondition::class,
            'key' => 'test',
        ];
        try {
            $this->factory->create($conditionSpec);
        } catch(\Exception $e) {
            self::assertInstanceOf(Exception\RuntimeException::class, $e);
            self::assertEquals('Не задано значение для Condition', $e->getMessage());
        }
    }

    public function testConditionNotImplementsInterface()
    {
        $conditionSpec = [
            'type' => Factory::class,
            'key' => 'test',
            'value' => 2
        ];
        try {
            $this->factory->create($conditionSpec);
        } catch(\Exception $e) {
            self::assertInstanceOf(Exception\RuntimeException::class, $e);
            self::assertEquals(sprintf('Condition должен реализовывать %s', ConditionInterface::class), $e->getMessage());
        }
    }
}