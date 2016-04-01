<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace NNX\DataGrid\Mutator;

use Zend\Mvc\Service\AbstractPluginManagerFactory;

/**
 * Class ColumnPluginManagerFactory
 * @package NNX\DataGrid\Column
 */
class GridMutatorPluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = GridMutatorPluginManager::class;
}
