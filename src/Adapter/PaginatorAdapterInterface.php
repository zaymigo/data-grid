<?php
/**
 * @year 2016
 * @author Lobanov Aleksandr <lobanov@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Adapter;

use Zend\Paginator\Adapter\AdapterInterface as ZendAdapterInterface;

/**
 * Interface PaginatorAdapterInterface
 * @package Nnx\DataGrid\Adapter
 */
interface PaginatorAdapterInterface extends ZendAdapterInterface
{
    /**
     * @param int $offset
     * @return $this
     */
    public function setOffset($offset);

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit($limit);
}