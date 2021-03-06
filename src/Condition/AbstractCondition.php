<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Condition;

/**
 * Class AbstractCondition
 * @package Nnx\DataGrid\Condition
 */
abstract class AbstractCondition implements ConditionInterface
{
    /**
     * @var int
     */
    protected $criteria;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param string $key
     * @param String $criteria
     * @param null | string | array | \Traversable $value
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($key, $criteria, $value=null)
    {
        if (!$key) {
            throw new Exception\InvalidArgumentException('Не задан ключ для создания Condition');
        }
        $this->setKey($key);

        if (!$criteria) {
            throw new Exception\InvalidArgumentException('Не задан критерий для Condition');
        }
        $this->setCriteria($criteria);
        $this->setValue($value);
    }

    /**
     * @return int
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     *
     * @param int $criteria
     * @return mixed
     */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}
