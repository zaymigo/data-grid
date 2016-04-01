<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace NNX\DataGrid;

use Zend\Mvc\Service\AbstractPluginManagerFactory;

/**
 * Class GridPluginManagerFactory 
 * @package NNX\DataGrid
 */
class GridPluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = GridPluginManager::class;
}
