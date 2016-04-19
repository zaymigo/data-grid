<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\View\Helper\JqGrid;

use Nnx\DataGrid\Mutator\HighlightMutatorInterface;
use Zend\View\Helper\AbstractHelper;
use Nnx\DataGrid\GridInterface;
use Zend\View\Helper\EscapeHtml;
use Zend\View\Renderer\PhpRenderer;
use Nnx\DataGrid\View\Helper\Exception;
use Nnx\DataGrid\NavigationBar\NavigationBarInterface;
use Nnx\DataGrid\Button\ButtonInterface;

/**
 * Class Grid
 * @package Nnx\DataGrid\View\Helper
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
        /** @var PhpRenderer $view */
        $view = $this->getView();
        /** @var EscapeHtml $escape */
        $escape = $view->plugin('escapeHtml');
        $bottomNavigationBar = $this->renderNavigationBar($grid->getBottomNavigationBar());
        $topNavigationBar = $this->renderNavigationBar($grid->getTopNavigationBar());
        $res = $topNavigationBar['html'] . '<table id="grid-' . $escape($grid->getName()) . '"></table>' . $bottomNavigationBar['html'];
        $buttonsJs = $topNavigationBar['js'] . $bottomNavigationBar['js'];
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $config = $this->getGridConfig($grid);
        foreach ($columns as $column) {
            $columnClass = get_class($column);
            $columnPath = explode('\\', $columnClass);
            $colName = array_pop($columnPath);
            $helperName = 'nnxGridJqGrid' . $colName;
            /** @var string $columnsJqOptions */
            $config['colModel'][] = $view->$helperName($column);
        }

        $rowAttr = null;
        if ($mutators = $grid->getMutators()) {
            foreach ($mutators as $mutator) {
                if ($mutator instanceof HighlightMutatorInterface) {
                    $config['rowattr'] = '%rowAttrFunction%';
                    $rowAttr = 'function(rd) {' .
                        'if(rd.' . $mutator->getDataName() . ') {'
                        . 'return {"class": rd.' . $mutator->getDataName() . '};'
                        . '}'
                        . '}';
                }
            }
        }
        $view->headScript()->appendScript('$(function(){'
            . 'var grid = $("#grid-' . $grid->getName() . '").jqGrid('
            . str_replace('"%rowAttrFunction%"', $rowAttr, json_encode((object)$config)) . ');'
            . str_replace('%gridName%', $grid->getName(), $buttonsJs) .'});');
        return $res;
    }

    /**
     * @param NavigationBarInterface $navigationBar
     * @return array
     */
    protected function renderNavigationBar(NavigationBarInterface $navigationBar = null)
    {
        $html = '';
        $js = '';
        if ($navigationBar) {
            /** @var PhpRenderer $view */
            $view = $this->getView();
            /** @var ButtonInterface $button */
            foreach ($navigationBar->getButtons() as $button) {
                $buttonResult = $view->nnxGridJqGridButton($button);
                $html .=  $buttonResult['html'];
                $js .=  $buttonResult['js'];
            }
            $html = "<div class='buttons-panel'>$html</div><br>";
        }
        return ['html' => $html,'js' => $js];
    }

    /**
     * @param GridInterface $grid
     * @return array
     */
    protected function getGridConfig(GridInterface $grid)
    {
        $attributes = $grid->getAttributes();
        $config = [
            'shrinkToFit' => false,
            'width' => $this->getConfigVal('width', $attributes, '100%'),
            'datatype' => $this->getConfigVal('datatype', $attributes, 'local')
        ];
        if (!array_key_exists('width', $attributes) && !array_key_exists('autowidth', $attributes)) {
            $config['autowidth'] = true;
        }

        $config = array_merge($config, $attributes);
        return $config;
    }

    /**
     * @param string $key
     * @param array $options
     * @param mixed $default
     * @return null|string|array
     */
    protected function getConfigVal($key, array $options, $default = null)
    {
        return array_key_exists($key, $options) ? $options[$key] : $default;
    }
}
