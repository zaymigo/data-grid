<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid;

use Zend\ServiceManager\AbstractPluginManager;

/**
 * Class GridPluginManager 
 * @package MteGrid\Grid
 */
class GridPluginManager extends AbstractPluginManager
{
    /**
     * @var bool
     */
    protected $shareByDefault = false;

    /**
     * Validate the plugin
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param  mixed $plugin
     * @return void
     * @throws Exception\RuntimeException if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof GridInterface) {
            return;
        }

        throw new Exception\RuntimeException(sprintf('Grid должен реализовывать интерфейс %s', GridInterface::class));
    }
}
