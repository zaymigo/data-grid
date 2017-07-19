<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Column;

use Nnx\DataGrid\Column\Exception\RuntimeException;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

/**
 * Class ColumnPluginManager
 * @package Nnx\DataGrid\Column
 */
class GridColumnPluginManager extends AbstractPluginManager
{

    /**
     * @var bool
     */
    protected $shareByDefault = false;

    /**
     * @var array
     */
    protected $aliases = [
        'text' => Text::class,
        'hidden' => Hidden::class,
        'link' => Link::class,
        'money' => Money::class,
        'radio' => Radio::class,
        'checkbox' => Checkbox::class,
        'action' => Action::class
    ];

    /**
     * @var array
     */
    protected $invokableClasses = [

    ];

    protected $factories = [
        Action::class => ActionFactory::class,
        Text::class => Factory::class,
        Hidden::class => Factory::class,
        Link::class => Factory::class,
        Radio::class => Factory::class,
        Checkbox::class => Factory::class,
        Money::class => Factory::class
    ];

    /**
     * Validate the plugin
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param  mixed $plugin
     * @return void
     * @throws RuntimeException if invalid
     */
    public function validate($plugin)
    {
        if ($plugin instanceof ColumnInterface) {
            return;
        }

        throw new RuntimeException(sprintf('Column должен реализовывать %s', ColumnInterface::class));
    }
}
