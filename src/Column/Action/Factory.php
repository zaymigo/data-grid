<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Column\Action;

use Interop\Container\ContainerInterface;
use Traversable;
use Zend\ServiceManager\Factory\FactoryInterface;


/**
 * Class Factory
 * @package Nnx\DataGrid\Column\Action
 */
class Factory implements FactoryInterface
{
    /**
     * @param array $spec
     * @throws Exception\RuntimeException
     */
    protected function validate($spec)
    {
        if (!array_key_exists('type', $spec) || !$spec['type']) {
            throw new Exception\RuntimeException(
                'Не задан тип действия'
            );
        }

        if (!array_key_exists('name', $spec) || !$spec['name']) {
            throw new Exception\RuntimeException(
                'У действия должно быть имя'
            );
        }
    }

    /**
     * Создает экземпляр объекта
     * @param array | Traversable | string $spec
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        //TODO fixme
        $action = new SimpleAction($options);
        if (array_key_exists('attributes', $options)) {
            $action->setAttributes($options['attributes']);
        }

        if (array_key_exists('validate', $options)) {
            $action->setValidationFunction($options['validate']);
        }
        return $action;
    }


}
