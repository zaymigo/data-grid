<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Options;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Nnx\DataGrid\Module;
use ArrayAccess;

/**
 * Class Factory
 * @package Nnx\DataGrid\Options
 */
class Factory implements FactoryInterface
{

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $options = [];
        if (array_key_exists(Module::CONFIG_KEY, $config) && $config[Module::CONFIG_KEY]) {
            if (!is_array($config[Module::CONFIG_KEY]) && $config[Module::CONFIG_KEY] instanceof ArrayAccess) {
                throw new Exception\RuntimeException(
                    sprintf('Конфиг опции модуля Grid должен быть массивом или %s', ArrayAccess::class)
                );
            }
            $options = $config[Module::CONFIG_KEY];
        }
        return new ModuleOptions($options);
    }
}
