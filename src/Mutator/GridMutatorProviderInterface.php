<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Mutator;

use Traversable;

/**
 * Interface GridColumnProviderInterface
 * @package MteGrid\Grid\Column
 */
interface GridMutatorProviderInterface
{
    /**
     * Возвращает конфигурацию мутаторов
     * @return array | Traversable
     */
    public function getGridMutatorConfig();
}
