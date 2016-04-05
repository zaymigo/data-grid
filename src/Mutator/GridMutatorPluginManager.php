<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Mutator;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

/**
 * Class GridMutatorPluginManager
 * @package Nnx\DataGrid\Column
 */
class GridMutatorPluginManager extends AbstractPluginManager
{

    /**
     * @var bool
     */
    protected $shareByDefault = false;

    /**
     * Алиасы для вызова мутаторов
     * @var array
     */
    protected $aliases = [
        'link' => Link::class,
        'money' => Money::class,
        'highlight' => Highlight::class
    ];

    /**
     * Классы мутаторов, которые может вызывать
     * данный мэнеджер
     * @var array
     */
    protected $invokableClasses = [
        Money::class => Money::class,

    ];

    protected $factories = [
        Link::class => LinkFactory::class,
        Highlight::class => Factory::class
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
