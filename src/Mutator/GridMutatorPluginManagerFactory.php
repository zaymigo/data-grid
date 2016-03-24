<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Mutator;

use Zend\Mvc\Service\AbstractPluginManagerFactory;

/**
 * Class ColumnPluginManagerFactory
 * @package MteGrid\Grid\Column
 */
class GridMutatorPluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = GridMutatorPluginManager::class;
}
