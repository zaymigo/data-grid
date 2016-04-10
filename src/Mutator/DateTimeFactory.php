<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Mutator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DateTimeFactory
 * @package Nnx\DataGrid\Mutator
 */
class DateTimeFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * Конструктор класса
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setCreationOptions($options);
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface|GridMutatorPluginManager $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if ($serviceLocator instanceof GridMutatorPluginManager) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }
        $dateTimeFormatter = $serviceLocator->get('FilterManager')->get('DateTimeFormatter');
        $options = $this->getCreationOptions();
        $options['dateTimeFormatter'] = $dateTimeFormatter;
        return new DateTime($options);
    }
}