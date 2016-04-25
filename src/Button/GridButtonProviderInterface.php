<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Button;

use Traversable;

/**
 * Interface GridButtonProviderInterface
 * @package Nnx\DataGrid\Button
 */
interface GridButtonProviderInterface
{
    /**
     * Возвращает конфигурацию кнопок
     * @return array | Traversable
     */
    public function getGridButtonConfig();
}
