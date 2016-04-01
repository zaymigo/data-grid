<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace NNX\DataGrid\Column;

use Traversable;

/**
 * Interface GridColumnProviderInterface
 * @package NNX\DataGrid\Column
 */
interface GridColumnProviderInterface
{
    /**
     * Возвращает конфигурацию колонок
     * @return array | Traversable
     */
    public function getGridColumnConfig();
}
