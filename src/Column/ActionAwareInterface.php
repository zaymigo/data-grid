<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Column;

use MteGrid\Grid\Column\Action\ActionInterface;

/**
 * Interface ActionAwareInterface
 * @package MteGrid\Grid\Column
 */
interface ActionAwareInterface
{
    /**
     * Получает набор действий
     * @return array
     */
    public function getActions();

    /**
     * Добавляет действия
     * @param array $actions
     * @return $this
     */
    public function setActions($actions);

    /**
     * Добавляет действие в колонку
     * @param array|ActionInterface $action
     * @return mixed
     */
    public function addAction($action);
}
