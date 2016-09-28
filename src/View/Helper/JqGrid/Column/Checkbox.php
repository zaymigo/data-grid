<?php
/**
 * @package Nnx\DataGrid
 * @author Lobanov Aleksandr
 */

namespace Nnx\DataGrid\View\Helper\JqGrid\Column;

use Nnx\DataGrid\Column\ColumnInterface;

/**
 * Class Checkbox
 * @package Nnx\DataGrid\View\Helper\JqGrid\Column
 */
class Checkbox extends Text
{

    /**
     * Возвращает конфигурацию колонки
     * @param ColumnInterface $column
     * @return array
     */
    protected function getColumnConfig(ColumnInterface $column)
    {
        $config = parent::getColumnConfig($column);

        $config = array_merge_recursive($config, [
            'formatter' => "checkbox",
            'formatoptions' => ['disabled' => false],
        ]);

        return $config;
    }
}
