<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid;

/**
 * Interface AjaxProviderInterface
 * @package Nnx\DataGrid
 */
interface AjaxProviderInterface
{
    /**
     * Устанавливает ссылку для ajax запроса
     * @param string $url
     * @return $this
     */
    public function setUrl($url);


    /**
     * Возвращает ссылку для ajax запроса
     * @return string
     */
    public function getUrl();
}
