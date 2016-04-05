<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid;

/**
 * Interface GridPluginManagerProviderInterface
 * @package Nnx\DataGrid
 */
interface GridProviderInterface
{
    /**
     * Возвращает конфигурацию таблиц
     * @return array
     */
    public function getGridConfig();
}
