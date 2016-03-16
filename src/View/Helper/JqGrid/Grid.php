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
        $config = $this->getGridConfig($grid);
        foreach ($columns as $column) {
            /** @var string $columnsJqOptions */
            $config['colModel'][] = $view->mteGridJqGridColumn($column);
        }
//        $config['data'] = $grid->getRowset();
//        echo '<pre>'; \Doctrine\Common\Util\Debug::dump($config['data']);die;
        $view->headScript()->appendScript('$(function(){'
            . '$("#grid-' . $grid->getName() . '").jqGrid(' . json_encode((object)$config) . ');});');
        return $res;
    }

    /**
     * @param GridInterface $grid
     * @return array
     */
    protected function getGridConfig(GridInterface $grid)
    {
        $attributes = $grid->getAttributes();
        $config = [];
        if (array_key_exists('width', $attributes) && $attributes['width']) {
            $config['width'] = $attributes['width'];
        }
        if (!array_key_exists('width', $config) && !array_key_exists('autowidth', $attributes)) {
            $config['autowidth'] = true;
        }
        return $config;
    }
}
