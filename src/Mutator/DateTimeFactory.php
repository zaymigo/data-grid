<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Mutator;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Filter\FilterPluginManager;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DateTimeFactory
 * @package Nnx\DataGrid\Mutator
 */
class DateTimeFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface|GridMutatorPluginManager $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

    }

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
//        if ($serviceLocator instanceof GridMutatorPluginManager) {
//            $serviceLocator = $serviceLocator->getServiceLocator();
//        }
        $dateTimeFormatter = $container->get("FilterManager")->get('DateTimeFormatter');
        if (!is_array($options)) {
            $options = [];
        }
        return new DateTime($dateTimeFormatter, $options);
    }
}
