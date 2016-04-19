<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Ushkov Nikolai <ushkov@mte-telecom.ru>
 * Date: 19.04.16
 * Time: 13:10
 */

namespace Nnx\DataGrid\Button;

/**
 * Class GridButtonPluginManagerAwareTrait
 * @package Nnx\DataGrid\Button
 */
trait GridButtonPluginManagerAwareTrait
{
    /**
     * Мэнеджер колонок
     * @var GridButtonPluginManager
     */
    protected $buttonPluginManager;

    /**
     * Возвращает менеджер колонок
     * @return GridButtonPluginManager
     */
    public function getButtonPluginManager()
    {
        return $this->buttonPluginManager;
    }

    /**
     * Устанавливает мэнджер колонок
     * @param GridButtonPluginManager $buttonPluginManager
     * @return $this
     */
    public function setButtonPluginManager(GridButtonPluginManager $buttonPluginManager)
    {
        $this->buttonPluginManager = $buttonPluginManager;
        return $this;
    }
}
