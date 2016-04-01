<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace NNX\DataGrid\Column\Action;

/**
 * Interface ActionInterface
 * @package NNX\DataGrid\Column\Action
 */
interface ActionInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Возвращает заголовок действия
     * @return string
     */
    public function getTitle();

    /**
     * Возвращает ссылку действия
     * @return string
     */
    public function getUrl();

    /**
     * Возвращает true если проверка прошла и false если проверка не пройдена
     * @return bool
     */
    public function validate();

    /**
     * @return array
     */
    public function getAttributes();

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes);
}
