<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\MappedSuperclass;

/**
 * Класс используется для построения деревьев
 * Class NestedSet
 * @package Nnx\DataGrid\Entity
 * @MappedSuperclass()
 */
class NestedSet
{
    use NestedSetTrait;
    /**
     * @var int
     *
     * @Id()
     * @Column(name="id",type="integer"),
     * @GeneratedValue(strategy = "IDENTITY")
     */
    protected $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}
