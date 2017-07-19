<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid;

use Traversable;
use Zend\ServiceManager\Factory\FactoryInterface as ServiceManagerFactoryInterface;

/**
 * Interface FactoryInterface
 * @package Nnx\DataGrid\Column
 */
interface FactoryInterface extends ServiceManagerFactoryInterface
{
    /**
     * Создает экземпляр объекта
     * @param array | Traversable | string $spec
     * @return mixed
     */
    public function create($spec);
}
