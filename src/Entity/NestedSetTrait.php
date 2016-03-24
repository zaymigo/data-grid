<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Entity;

use Doctrine\ORM\Mapping\Column;

/**
 * Trait NestedSetTrait
 * @package MteGrid\Grid\Entity
 */
trait NestedSetTrait
{
    /**
     * @var int
     *
     * @Column(name="lft", type="integer", nullable=true)
     *
     */
    protected $left;

    /**
     * @var int
     *
     * @Column(name="rgt", type="integer", nullable=true)
     */
    protected $right;

    /**
     * @var int
     *
     * @Column(name="level", type="integer",  nullable=true)
     */
    protected $level;

    /**
     * @return int
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @param int $left
     * @return $this
     */
    public function setLeft($left)
    {
        $this->left = $left;
        return $this;
    }

    /**
     * @return int
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @param int $right
     * @return $this
     */
    public function setRight($right)
    {
        $this->right = $right;
        return $this;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param int $level
     * @return $this
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }
}
