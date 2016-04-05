<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Column;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ActionFactory
 * @package Nnx\DataGrid\Column
 */
class ActionFactory extends Factory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->setColumnPluginManager($serviceLocator);
        if ($serviceLocator instanceof GridColumnPluginManager) {
            $helper = $serviceLocator->getServiceLocator()->get('ViewHelperManager')->get('Url');
        } else {
            $helper = $serviceLocator->get('ViewHelperManager')->get('Url');
        }
        $options = $this->getCreationOptions();
        $options['urlHelper'] = $helper;
        $header = $this->createHeader($this->getCreationOptions());
        $options['header'] = $header;
        return new Action($options);
    }
}
