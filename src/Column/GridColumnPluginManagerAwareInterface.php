<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace NNX\DataGrid\Column;

/**
 * Interface GridColumnPluginManagerProviderInterface
 * @package NNX\DataGrid\Column
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
