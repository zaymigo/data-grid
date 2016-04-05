<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Column\Action;

use Nnx\DataGrid\FactoryInterface;
use Traversable;


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
    public function create($spec)
    {
        /**
         * @TODO fixme
         */
        $action = new SimpleAction($spec);
        if (array_key_exists('attributes', $spec)) {
            $action->setAttributes($spec['attributes']);
        }

        if (array_key_exists('validate', $spec)) {
            $action->setValidationFunction($spec['validate']);
        }
        return $action;
    }
}
