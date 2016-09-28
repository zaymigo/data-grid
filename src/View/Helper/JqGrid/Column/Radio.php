<?php
/**
 * @package Nnx\DataGrid
 * @author Lobanov Aleksandr
 */

namespace Nnx\DataGrid\View\Helper\JqGrid\Column;

use Nnx\DataGrid\Column\ColumnInterface;

/**
 * Class Radio
 * @package Nnx\DataGrid\View\Helper\JqGrid\Column
 */
class Radio extends Text
{
    /**
     * Возвращает конфигурацию колонки
     * @param ColumnInterface $column
     * @return array
     */
    protected function getColumnConfig(ColumnInterface $column)
    {
        $config = parent::getColumnConfig($column);
        $config['formatter'] = 'radio';
        return $config;
    }
}
