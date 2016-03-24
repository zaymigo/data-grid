<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\View\Helper\JqGrid\Column;

use MteGrid\Grid\Column\ColumnInterface;
use Zend\Form\View\Helper\AbstractHelper;
use Zend\View\Renderer\PhpRenderer;

/**
 * Class Hidden
 * @package MteGrid\Grid\View\Helper\Column
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
