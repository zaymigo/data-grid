<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace NNX\DataGrid\Column\Header;

use NNX\DataGrid\Column\Header\Exception\NoValidTemplateException;
use Traversable;

/**
 * Class AbstractHeader
 * @package NNX\DataGrid\Column\Header
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
     * Заголовок
     * @var string
     */
    protected $title;

    /**
     * @param string $title
     * @param string $template
     * @param array | Traversable $data
     * @param array | Traversable $options
     * @throws NoValidTemplateException
     */
    public function __construct($title = '', $template = '', array $data = [], array $options = [])
    {
        if ($template && !is_string($template)) {
            throw new NoValidTemplateException(
                sprintf('Невалидный путь до шаблона заголовка.')
            );
        }
        if ($template) {
            $this->setTemplate($template);
        }
        if ($title) {
            $this->setTitle($title);
        }
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

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
}
