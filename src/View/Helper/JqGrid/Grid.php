<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\View\Helper\JqGrid;

use MteGrid\Grid\Row;
use Zend\View\Helper\AbstractHelper;
use MteGrid\Grid\GridInterface;
use Zend\View\Helper\EscapeHtml;
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
        /** @var PhpRenderer $view */
        $view = $this->getView();
        /** @var EscapeHtml $escape */
        $escape = $view->plugin('escapeHtml');
        $res = '<table id="grid-' . $escape($grid->getName()) . '"></table>';
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $config = $this->getGridConfig($grid);
        foreach ($columns as $column) {
            /** @var string $columnsJqOptions */
            $config['colModel'][] = $view->mteGridJqGridColumn($column);
        }
        $data = $grid->getRowset();

        $config['data'] = array_map(function ($item) {
            /** @var Row $item */
            $item = $item->getData();
            return $item;
        }, $data);
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
        $config = [
            'shrinkToFit' => false
        ];
        $config['width'] = $this->getConfigVal('width', $attributes, '100%');
        if (!array_key_exists('width', $attributes) && !array_key_exists('autowidth', $attributes)) {
            $config['autowidth'] = true;
        }

        $config['datatype'] = $this->getConfigVal('datatype', $attributes, 'local');
        $config = array_merge($config, $attributes);
        return $config;
    }

    /**
     * @param string $key
     * @param array $options
     * @param mixed $default
     * @return null | string | array
     */
    protected function getConfigVal($key, array $options, $default = null)
    {
        return array_key_exists($key, $options) ? $options[$key] : $default;
    }
}
