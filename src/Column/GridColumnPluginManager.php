<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace NNX\DataGrid\Column;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

/**
 * Class ColumnPluginManager
 * @package NNX\DataGrid\Column
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
        'action' => Action::class
    ];

    /**
     * @var array
     */
    protected $invokableClasses = [
        Text::class => Text::class,
        Hidden::class => Hidden::class,
        Link::class => Link::class,
        Money::class => Money::class
    ];

    protected $factories = [
        Action::class => ActionFactory::class
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
        if ($plugin instanceof ColumnInterface) {
            return;
        }

        throw new Exception\RuntimeException('Column должен реализовывать %s', ColumnInterface::class);
    }
}
