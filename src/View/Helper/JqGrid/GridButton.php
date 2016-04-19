<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Ushkov Nikolai <ushkov@mte-telecom.ru>
 * Date: 19.04.16
 * Time: 14:45
 */

namespace Nnx\DataGrid\View\Helper\JqGrid;

use Zend\View\Helper\AbstractHelper;
use Nnx\DataGrid\Button\ButtonInterface;
use Zend\View\Helper\EscapeHtml;
use Zend\View\Renderer\PhpRenderer;

/**
 * Class GridButton
 * @package Nnx\DataGrid\View\Helper\JqGrid
 */
class GridButton extends AbstractHelper
{
    public function __invoke(ButtonInterface $button)
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        /** @var EscapeHtml $escape */
        $escape = $view->plugin('escapeHtml');

        $attributeString = $this->getAttributesString($button->getAttributes());
        $url = $button->getUrl();
        $title = $escape($button->getTitle());
        $html = "<a href='$url' $attributeString>$title</a> ";
        $js = $button->getJs();
        return ['html' => $html,'js' => $js];
    }

    /**
     * @param array $attributes
     */
    protected function getAttributesString($attributes)
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        /** @var EscapeHtml $escape */
        $escape = $view->plugin('escapeHtml');
        $res = '';
        foreach ($attributes as $name=>$value) {
            $res .= $escape($name) . '="'.$escape($value).'" ';
        }
        return $res;
    }
}
