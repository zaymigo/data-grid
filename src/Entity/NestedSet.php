<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Entity;

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
     * @Id()
     *
     * @var int
     */
    protected $id;

    /**
     * @var int
     *
     */
    protected $left;

    /**
     * @var int
     */
    protected $right;

    /**
     * @var int
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
