<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Column;

/**
 * Interface GridColumnPluginManagerProviderInterface
 * @package MteGrid\Grid\Column
 */
interface GridColumnPluginManagerAwareInterface
{
    /**
     * Возвращает GridColumnPluginManager
     * @return GridColumnPluginManager
     */
    public function getColumnPluginManager();

    /**
     * Устанавливает GridColumnPluginManager
     * @param GridColumnPluginManager $gridColumnPluginManager
     * @return $this
     */
    public function setColumnPluginManager(GridColumnPluginManager $gridColumnPluginManager);
}
