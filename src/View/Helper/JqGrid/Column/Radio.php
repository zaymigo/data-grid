<?php


namespace Nnx\DataGrid\View\Helper\JqGrid\Column;


use Nnx\DataGrid\Column\ColumnInterface;
use Zend\Json;

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
        $config['formatter'] = new Json\Expr(<<<JS

    function radio(value, options, rowObject){

        var result = '<input type="radio" name="radio-{$column->getName()}"';
        if(value) {
            result += 'value=' + value;
        }
        result += ' />';

        return result;
    }

JS
        );
        return $config;
    }

}