<?php
/**
 * @year 2016
 * @author Lobanov Aleksandr <lobanov@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Paginator;

use \Zend\Paginator\Paginator as BasePaginator;

/**
 * Class Paginator
 * @package Nnx\DataGrid\Paginator
 */
class Paginator extends BasePaginator implements PaginatorInterface
{

    /**
     * Returns the possible number of items per page.
     *
     * @var array
     */
    protected $possibleItemCountPerPage = [10, 25, 50];

    /**
     * @return array
     */
    public function getPossibleItemCountPerPage()
    {
        return $this->possibleItemCountPerPage;
    }

    /**
     * @param array $possibleItemCountPerPage
     * @return $this
     */
    public function setPossibleItemCountPerPage($possibleItemCountPerPage)
    {
        $this->possibleItemCountPerPage = $possibleItemCountPerPage;
        return $this;
    }


}