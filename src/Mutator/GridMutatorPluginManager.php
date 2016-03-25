<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Mutator;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

/**
 * Class GridMutatorPluginManager
 * @package MteGrid\Grid\Column
 */
class GridMutatorPluginManager extends AbstractPluginManager
{

    /**
     * @var bool
     */
    protected $shareByDefault = false;

    /**
     * @var array
     */
    protected $aliases = [
        'link' => Link::class,
        'money' => Money::class
    ];

    protected $invokableClasses = [
        Money::class => Money::class
    ];

    protected $factories = [
        Link::class => LinkFactory::class,

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
        if ($plugin instanceof MutatorInterface) {
            return;
        }

        throw new Exception\RuntimeException('Mutator должен реализовывать %s', MutatorInterface::class);
    }
}
