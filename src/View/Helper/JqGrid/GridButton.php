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
    /**
     * @var array
     */
    protected $variables = [];

    public function __invoke(ButtonInterface $button, $variables)
    {
        $this->variables = $variables;
        /** @var PhpRenderer $view */
        $view = $this->getView();
        /** @var EscapeHtml $escape */
        $escape = $view->plugin('escapeHtml');

        $attributeString = $this->getAttributesString($button->getAttributes());
        $url = $button->getUrl();
        if (is_array($url)) {
            $routeName = '';
            $routeParams = [];
            $routeOptions = [];
            if (array_key_exists('routeName', $url)) {
                $routeName = $url['routeName'];
            }
            if (array_key_exists('routeParams', $url)) {
                $routeParams = $url['routeParams'];
            }
            if (array_key_exists('routeOptions', $url)) {
                $routeOptions = $url['routeOptions'];
            }
            $url = $view->url($routeName, $routeParams, $routeOptions);
        }
        $url = $this->getUrl($url);
        $title = $escape($button->getTitle());
        $html = "<a href='$url' $attributeString>$title</a> ";
        $js = $button->getJs();
        return ['html' => $html,'js' => $js];
    }

    /**
     * @param $attributes
     * @return string
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

    /**
     * @param string $urlTemplate
     * @return string
     */
    public function getUrl($urlTemplate)
    {
        return preg_replace_callback('/:([a-zA-Z_]+)/', [$this, 'replaceCallback'], $urlTemplate);
    }

    /**
     * @param array $matches
     * @return mixed|string
     */
    protected function replaceCallback($matches)
    {
        $varName = $matches[1];
        $value = '';

        if (array_key_exists($varName, $this->variables)) {
            if ($varName !== 'backurl') {
                $value = urlencode($this->variables[$varName]);
            } else {
                $value = $this->variables[$varName];
            }
        }

        return $value;
    }
}
