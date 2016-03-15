<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\View\Helper\JqGrid;

use Zend\View\Helper\AbstractHelper;
use MteGrid\Grid\Column\ColumnInterface;

/**
 * Class Column
 * @package MteGrid\Grid\View\Helper
 */
class Column extends AbstractHelper
{
    /**
     * @param ColumnInterface $column
     * @return string
     */
    public function __invoke(ColumnInterface $column)
    {
        /** @var  $escaper */
        $escape = $this->getView()->plugin('escapeHtml');
        $name = $escape($column->getName());
        return '{'
        . '"label": "' . $escape($column->getHeader()->getTitle()) . '",'
        . '"index": "' . $name . '",'
        . '"name": "' . $name . '"'
        . '}';
    }
}
