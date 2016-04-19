<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Ushkov Nikolai <ushkov@mte-telecom.ru>
 * Date: 18.04.16
 * Time: 18:08
 */

namespace Nnx\DataGrid\Button;

use Zend\Mvc\Service\AbstractPluginManagerFactory;

/**
 * Class GridButtonPluginManagerFactory
 * @package Nnx\DataGrid\Button
 */
class GridButtonPluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = GridButtonPluginManager::class;
}
