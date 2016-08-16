<?php
/**
 * @year 2016
 * @author Lobanov Aleksandr <lobanov@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Adapter;

use Zend\Paginator\Adapter\AdapterInterface;

/**
 * Interface PaginatorAdapterInterface
 * @package Nnx\DataGrid\Adapter
 */
interface PaginatorAdapterInterface extends AdapterInterface
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