<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Column\Header;

use MteGrid\Grid\Column\Header\Exception\NoValidTemplateException;
use Traversable;

/**
 * Class AbstractHeader
 * @package MteGrid\Grid\Column\Header
 */
abstract class AbstractHeader implements HeaderInterface
{
    /**
     * Путь до шаблона
     * @var string
     */
    protected $template;

    /**
     * Опции заголовка
     * @var array | Traversable
     */
    protected $options;

    /**
     * Данные для
     * @var array | \Traversable
     */
    protected $data;

    /**
     * @param string $template
     * @param array | Traversable  $data
     * @param array | Traversable $options
     * @throws NoValidTemplateException
     */
    public function __construct($template = '', $data = [], $options = [])
    {
        if (!is_string($template) || !$template) {
            throw new NoValidTemplateException(
                sprintf('Невалидный путь до шаблона заголовка.')
            );
        }
        $this->setTemplate($template);
        $this->setData($data);
        $this->setOptions($options);
    }

    /**
     * Устанавливает шаблон для заголовка табоицы
     * @param $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * возвращает путь до шаблона
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Усанавливает опции для заголовка
     * @param array|Traversable $options
     * @return mixed
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Возвращает набор опций для заголовка
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Данные для шаблона заголовка
     * @param array | \Traversable $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Возвращает данные для шаблона
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
