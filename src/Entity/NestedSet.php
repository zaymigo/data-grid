<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\MappedSuperclass;

/**
 * Класс используется для построения деревьев
 * Class NestedSet 
 * @package MteGrid\Grid\Entity
 * @MappedSuperclass()
 */
class NestedSet
{
    /**
     * @var int
     *
     * @Id()
     * @Column(name="id",type="integer"),
     * @GeneratedValue(strategy = "IDENTITY")
     */
    protected $id;

    /**
     * @var int
     *
     * @Column(name="left", type="integer")
     *
     */
    protected $left;

    /**
     * @var int
     *
     * @Column(name="right", type="integer")
     */
    protected $right;

    /**
     * @var int
     *
     * @Column(name="level", type="integer")
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
