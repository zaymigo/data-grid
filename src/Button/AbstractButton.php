<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Ushkov Nikolai <ushkov@mte-telecom.ru>
 * Date: 18.04.16
 * Time: 17:16
 */

namespace Nnx\DataGrid\Button;

/**
 * Class AbstractButton
 * @package Nnx\DataGrid\Button
 */
abstract class AbstractButton implements ButtonInterface
{
    /**
     * Наименование кнопки.
     * @var string
     */
    protected $name;

    /**
     * url кнопки
     * @var string
     */
    protected $url;

    /**
     * Заголовок кнопки
     * @var string
     */
    protected $title;

    /**
     * JS код кнопки
     * @var string
     */
    protected $js;

    /**
     * JS библиотеки кнопки
     * @var array
     */
    protected $libJs = [];

    /**
     * Опции кнопки
     * @var array | \Traversable
     */
    protected $options = [];

    /**
     * Атрибуты кнопки
     * @var array | \Traversable
     */
    protected $attributes = [];

    /**
     * По данному полю осуществляется сортировка кнопок при выводе
     * @var int
     */
    protected $order = 0;

    /**
     * Устанавливает свойства класса
     * @param string $key
     * @param array $options
     */
    protected function setProperty($key, &$options)
    {
        if (array_key_exists($key, $options)) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                $this->$setter($options[$key]);
                unset($options[$key]);
            }
        }
    }

    /**
     * @param $options
     */
    public function __construct($options)
    {
        unset($options['type']);
        if (!empty($options['attributes']) && $this->getAttributes()) {
            $this->setAttributes(array_merge($this->getAttributes(), $options['attributes']));
            unset($options['attributes']);
        }
        foreach ($options as $key => $option) {
            $this->setProperty($key, $options);
        }
    }


    /**
     * Получить Наименование кнопки
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Установить Наименование кнопки
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Получить url кнопки
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Установить url кнопки
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Получить Заголовок кнопки
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Установить Заголовок кнопки
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Получить JS код кнопки
     * @return string
     */
    public function getJs()
    {
        return $this->js;
    }

    /**
     * Установить JS код кнопки
     * @param string $js
     * @return $this
     */
    public function setJs($js)
    {
        $this->js = $js;
        return $this;
    }

    /**
     * Получить JS библитеки кнопки
     * @return array
     */
    public function getLibJs()
    {
        return $this->libJs;
    }

    /**
     * Установить JS библитеки кнопки
     * @param array $libJs
     * @return $this
     */
    public function setLibJs($libJs)
    {
        $this->libJs = $libJs;
        return $this;
    }



    /**
     * Получить Опции кнопки
     * @return array|\Traversable
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Установить Опции кнопки
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Возвращает атрибут кнопки
     * @param $name
     * @return mixed|null
     */
    public function getAttribute($name)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
        return null;
    }

    /**
     * Получить Атрибуты кнопки
     * @return array|\Traversable
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Установить Аттрибут кнопки
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }
    /**
     * Установить Атрибуты кнопки
     * @param array|\Traversable $attributes
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Получить Значение сортировки
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Установить Значение сортировки
     * @param int $order
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }
}
