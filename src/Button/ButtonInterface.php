<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Ushkov Nikolai <ushkov@mte-telecom.ru>
 * Date: 18.04.16
 * Time: 17:23
 */

namespace Nnx\DataGrid\Button;

/**
 * Interface ButtonInterface
 * @package Nnx\DataGrid\Button
 */
interface ButtonInterface
{
    /**
     * Получить Наименование кнопки
     * @return string
     */
    public function getName();

    /**
     * Установить Наименование кнопки
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Получить url кнопки
     * @return string
     */
    public function getUrl();

    /**
     * Установить url кнопки
     * @param string $url
     * @return $this
     */
    public function setUrl($url);

    /**
     * Получить Заголовок кнопки
     * @return string
     */
    public function getTitle();

    /**
     * Установить Заголовок кнопки
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Получить JS код кнопки
     * @return string
     */
    public function getJs();

    /**
     * Установить JS код кнопки
     * @param string $js
     * @return $this
     */
    public function setJs($js);

    /**
     * Получить JS библитеки кнопки
     * @return array
     */
    public function getLibJs();

    /**
     * Установить JS библитеки кнопки
     * @param array $libJs
     * @return $this
     */
    public function setLibJs($libJs);

    /**
     * Получить Опции кнопки
     * @return array|\Traversable
     */
    public function getOptions();

    /**
     * Установить Опции кнопки
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options);

    /**
     * Возвращает атрибут кнопки
     * @param $name
     * @return mixed|null
     */
    public function getAttribute($name);

    /**
     * Получить Атрибуты кнопки
     * @return array|\Traversable
     */
    public function getAttributes();

    /**
     * Установить Аттрибут кнопки
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setAttribute($name, $value);
    /**
     * Установить Атрибуты кнопки
     * @param array|\Traversable $attributes
     * @return $this
     */
    public function setAttributes($attributes);

    /**
     * Получить Значение сортировки
     * @return int
     */
    public function getOrder();

    /**
     * Установить Значение сортировки
     * @param int $order
     * @return $this
     */
    public function setOrder($order);
}
