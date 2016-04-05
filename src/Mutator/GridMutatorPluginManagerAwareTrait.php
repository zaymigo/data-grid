<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Mutator;

/**
 * Class GridMutatorPluginManagerAwareTrait
 * @package Nnx\DataGrid\Mutator
 */
trait GridMutatorPluginManagerAwareTrait
{
    /**
     * Мэнеджер мутаторов
     * @var GridMutatorPluginManager
     */
    protected $mutatorPluginManager;

    /**
     * Возвращает мэнеджер мутаторов
     * @return GridMutatorPluginManager
     */
    public function getMutatorPluginManager()
    {
        return $this->mutatorPluginManager;
    }

    /**
     * Устанавливает мэнеджер мутаторов
     * @param GridMutatorPluginManager $mutatorPluginManager
     * @return $this
     */
    public function setMutatorPluginManager(GridMutatorPluginManager $mutatorPluginManager)
    {
        $this->mutatorPluginManager = $mutatorPluginManager;
        return $this;
    }
}