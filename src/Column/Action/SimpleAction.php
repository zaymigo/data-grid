<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Column\Action;

use Nnx\DataGrid\RowDataAwareInterface;
use Nnx\DataGrid\RowDataAwareTrait;

/**
 * Class SimpleAction
 * @package Nnx\DataGrid\Column\Action
 */
class SimpleAction extends AbstractAction implements RowDataAwareInterface
{

    use RowDataAwareTrait;

    /**
     * @return string
     */
    public function getUrl()
    {
        $urlHelper = $this->getUrlHelper();
        $route = $this->getRoute();
        $routeName = array_key_exists('routeName', $route) ? $route['routeName'] : null;
        $routeParams = array_key_exists('routeParams', $route) ? $route['routeParams'] : null;
        $routeOptions = array_key_exists('routeOptions', $route) ? $route['routeOptions'] : [];
        $options = array_merge($routeOptions, $this->getRowData());
        $url = preg_replace_callback('/:([a-zA-Z_]+)/',
            [$this, 'replaceCallback'],
            $urlHelper($routeName, $routeParams, $options)
        );
        return $url;
    }

    /**
     * @param array $matches
     * @return mixed|string
     */
    protected function replaceCallback($matches)
    {
        $row = $this->getRowData();
        $varName = $matches[1];
        $value = '';

        if (array_key_exists($varName, $row)) {
            if ($varName !== 'backurl') {
                $value = urlencode($row[$varName]);
            } else {
                $value = $row[$varName];
            }
        }

        return $value;
    }

    /**
     * @return bool|mixed
     */
    public function validate()
    {
        $res = true;
        if ($this->getValidationFunction()) {
            $res = call_user_func($this->getValidationFunction(), $this->getRowData(), $this);
        }
        return $res;
    }
}
