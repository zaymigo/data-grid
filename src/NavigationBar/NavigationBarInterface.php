<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Ushkov Nikolai <ushkov@mte-telecom.ru>
 * Date: 18.04.16
 * Time: 16:54
 */

namespace Nnx\DataGrid\NavigationBar;

/**
 * Interface NavigationBarInterface
 * @package Nnx\DataGrid\NavigationBar
 */
interface NavigationBarInterface
{
    /**
     * добавить кнопку
     * @param $button
     * @return $this
     */
    public function add($button);

    /**
     * удалить кнопку
     * @param $name
     * @return $this
     */
    public function remove($name);

    /**
     * получить кнопки бара
     * @return array
     */
    public function getButtons();

    /**
     * установить кнопки бара
     * @param array $buttons
     * @return $this
     */
    public function setButtons($buttons);

    /**
     * Опции навигационного бара
     * @param array  $options
     * @return $this
     */
    public function setOptions($options);

    /**
     * Возвращает Опции навигационного бара
     * @return array
     */
    public function getOptions();
}
