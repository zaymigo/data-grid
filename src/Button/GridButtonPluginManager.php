<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Ushkov Nikolai <ushkov@mte-telecom.ru>
 * Date: 18.04.16
 * Time: 18:00
 */

namespace Nnx\DataGrid\Button;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

/**
 * Class GridButtonPluginManager
 * @package Nnx\DataGrid\Button
 */
class GridButtonPluginManager extends AbstractPluginManager
{
    /**
     * @var bool
     */
    protected $shareByDefault = false;

    /**
     * @var array
     */
    protected $aliases = [
        'simple' => Simple::class,
        'expandall' => ExpandAll::class,
        'collapseall' => CollapseAll::class,
        'showhidecolumns' => ShowHideColumns::class,
    ];

    /**
     * @var array
     */
    protected $invokableClasses = [

    ];

    protected $factories = [
        Simple::class => Factory::class,
        ExpandAll::class => Factory::class,
        CollapseAll::class => Factory::class,
        ShowHideColumns::class => Factory::class,
    ];

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
        if ($plugin instanceof ButtonInterface) {
            return;
        }

        throw new Exception\RuntimeException(sprintf('Button должен реализовывать %s', ButtonInterface::class));
    }
}
