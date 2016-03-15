<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\View\Helper\JqGrid;

use Zend\View\Helper\AbstractHelper;
use MteGrid\Grid\GridInterface;
use Zend\View\Renderer\PhpRenderer;
use MteGrid\Grid\View\Helper\Exception;

/**
 * Class Grid
 * @package MteGrid\Grid\View\Helper
 */
class Grid extends AbstractHelper
{
    /**
     * @param GridInterface $grid
     * @return string
     * @throws Exception\RuntimeException
     */
    public function __invoke(GridInterface $grid)
    {
        $columns = $grid->getColumns();
        if (count($columns) === 0) {
            throw new Exception\RuntimeException('В гриде нет колонок!');
        }

//        uasort($columns, function ($new, $old) {
//            /**
//             * @var ColumnInterface $new
//             * @var ColumnInterface $old
//             */
//            switch (true) {
//                case $new->getOrder() < $old->getOrder():
//                    $res = -1;
//                    break;
//                case $new->getOrder() > $old->getOrder():
//                    $res = 1;
//                    break;
//                default:
//                    $res = 0;
//            }
//            return $res;
//        });
        /** @var callable $escaper */
        $escape = $this->getView()->plugin('escapeHtml');
        $res = '<table id="grid-' . $escape($grid->getName()) . '"></table>';
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $columnsJqOptions = [];
        foreach ($columns as $column) {
            /** @var string $columnsJqOptions */
            $columnsJqOptions[] = $view->mteGridJqGridColumn($column);
        }
        $view->headScript()->appendScript('$(function(){'
            . '$("#grid-' . $grid->getName() . '").jqGrid({'
            . '"colModel":[' . implode(',', $columnsJqOptions) . ']'
            . '});});');
        return $res;
    }
}
