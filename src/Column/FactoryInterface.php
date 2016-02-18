<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Column;

use Traversable;

/**
 * Interface FactoryInterface
 * @package MteGrid\Grid\Column
 */
interface FactoryInterface
{
    /**
     * Создает экземпляр объекта
     * @param array | Traversable $spec
     * @return mixed
     */
    public function create($spec);
}
