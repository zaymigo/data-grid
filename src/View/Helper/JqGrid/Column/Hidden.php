<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\View\Helper\JqGrid\Column;

use Nnx\DataGrid\Column\ColumnInterface;
use Zend\Form\View\Helper\AbstractHelper;
use Zend\View\Renderer\PhpRenderer;

/**
 * Class Hidden
 * @package Nnx\DataGrid\View\Helper\Column
 */
class Hidden extends AbstractHelper
{

    public function __invoke(ColumnInterface $column)
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        /** @var \Zend\View\Helper\EscapeHtml $escape */
        $escape = $view->plugin('escapeHtml');
        $config = [
            'hidden' => true,
            'name' => $escape($column->getName())
        ];
        $config = array_merge($config, $column->getAttributes());
        return (object)$config;
    }
}
