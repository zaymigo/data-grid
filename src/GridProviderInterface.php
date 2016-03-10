<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid;

/**
 * Interface GridPluginManagerProviderInterface
 * @package MteGrid\Grid
 */
interface GridProviderInterface
{
    /**
     * Возвращает конфигурацию таблиц
     * @return array
     */
    public function getGridConfig();
}