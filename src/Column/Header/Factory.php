<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Column\Header;

use MteGrid\Grid\Column\FactoryInterface;
use MteGrid\Grid\Column\Header\Exception\NoValidSpecificationException;
use MteGrid\Grid\Column\Header\Exception\NoValidTemplateException;
use Traversable;
/**
 * Фабрика заголовков для колнок
 * Class Factory
 * @package MteGrid\Grid\Column\Header
 *
 */
class Factory implements FactoryInterface
{
    /**
     * Валидирует пришедшие данные для создания заголовка колонки
     * @param array | Traversable $spec
     * @throws NoValidSpecificationException
     * @throws NoValidTemplateException
     */
    protected function validate($spec)
    {
        if (!is_array($spec)
            && !$spec instanceof Traversable) {
            throw new NoValidSpecificationException(
                sprintf('Спецификация для создания заголовка колонки должна быть массивом или реализовывать %s',
                    Traversable::class)
            );
        }
        if (!array_key_exists('template', $spec) || !$spec['template']) {
            throw new NoValidTemplateException(
                sprintf('Не задан шаблон для заголовка.')
            );
        }
    }


    /**
     * Создает экземпляр класса фабрики
     * @param array|Traversable $spec
     * @throws NoValidSpecificationException
     * @throws NoValidTemplateException
     * @return HeaderInterface
     */
    public function create($spec)
    {
        $this->validate($spec);

        $header = new SimpleHeader($spec['template']);

        if (array_key_exists('options', $spec) && $spec['options']) {
            $header->setOptions($spec['options']);
        }

        if (array_key_exists('data', $spec) && $spec['data']) {
            $header->setData($spec['data']);
        }
        return $header;
    }
}
