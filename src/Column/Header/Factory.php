<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Column\Header;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Nnx\DataGrid\Column\Header\Exception\NoValidSpecificationException;
use Nnx\DataGrid\Column\Header\Exception\NoValidTemplateException;
use Traversable;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Фабрика заголовков для колонок таблицы
 * Class Factory
 * @package Nnx\DataGrid\Column\Header
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
    }


    protected function checkOption($key, $options)
    {
        $option = null;
        if (array_key_exists($key, $options) && $options[$key]) {
            $option = $options[$key];
        }
        return $option;
    }


    /**
     * Создает экземпляр класса фабрики
     * @param array|Traversable $options
     * @throws NoValidSpecificationException
     * @throws NoValidTemplateException
     * @return HeaderInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->validate($options);
        $specOptions = $this->checkOption('options', $options);
        $data = $this->checkOption('data', $options);
        $title = $this->checkOption('title', $options);
        $template = $this->checkOption('template', $options);
        $header = new SimpleHeader($title, $template, ($data ?: []), ($specOptions ?: []));
        return $header;
    }
}
