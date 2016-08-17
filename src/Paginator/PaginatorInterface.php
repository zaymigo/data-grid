<?php
/**
 * @year 2016
 * @author Lobanov Aleksandr <lobanov@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Paginator;

use Countable;

/**
 * Interface PaginatorInterface
 * @package Nnx\DataGrid\Paginator
 */
interface PaginatorInterface extends Countable
{

    /**
     * Returns the possible number of items per page.
     *
     * @return int
     */
    public function getPossibleItemCountPerPage();

    /**
     * Sets the number of items per page.
     *
     * @param  int $itemCountPerPage
     * @return PaginatorInterface $this
     */
    public function setItemCountPerPage($itemCountPerPage = -1);

    /**
     * Returns the number of items per page.
     *
     * @return int
     */
    public function getItemCountPerPage();

    /**
     * Sets the current page number.
     *
     * @param  int $pageNumber Page number
     * @return PaginatorInterface $this
     */
    public function setCurrentPageNumber($pageNumber);

    /**
     * Returns the current page number.
     *
     * @return int
     */
    public function getCurrentPageNumber();

}