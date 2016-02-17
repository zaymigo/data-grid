<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Column;

use Traversable;

/**
 * Interface GridColumnProviderInterface
 * @package MteGrid\Grid\Column
 */
interface GridColumnProviderInterface
{
    /**
     * Возвращает конфигурацию колонок
     * @return array | Traversable
     */
    public function getGridColumnConfig();
}
