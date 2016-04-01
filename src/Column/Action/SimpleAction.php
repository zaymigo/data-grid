<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace NNX\DataGrid\Column\Action;

use NNX\DataGrid\RowDataAwareInterface;
use NNX\DataGrid\RowDataAwareTrait;

/**
 * Class SimpleAction
 * @package NNX\DataGrid\Column\Action
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
        $routeOptions = array_key_exists('routeOptions', $route) ? $route['options'] : [];
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

        if (isset($row[$varName])) {
            if ($varName !== 'backurl') {
                $value = urlencode($row[$varName]);
            } else {
                $value = $row[$varName];
            }
        }

        return $value;
    }

    public function validate()
    {
        $res = true;
        if ($this->getValidationFunction()) {
            $res = call_user_func($this->getValidationFunction(), $this->getRowData());
        }
        return $res;
    }
}
