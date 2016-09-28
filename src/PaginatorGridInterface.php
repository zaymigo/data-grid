<?php
/**
 * @year 2016
 * @author Lobanov Aleksandr <lobanov@mte-telecom.ru>
 */

namespace Nnx\DataGrid;

/**
 * Interface PaginatorGridInterface
 * @package Nnx\DataGrid
 */
interface PaginatorGridInterface
{
    /**
     * @return Paginator\PaginatorInterface
     */
    public function getPaginator();
}
