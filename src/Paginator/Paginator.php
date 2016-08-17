<?php
/**
 * @year 2016
 * @author Lobanov Aleksandr <lobanov@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Paginator;

use Nnx\DataGrid\Adapter\PaginatorAdapterInterface;
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
    public function setPossibleItemCountPerPage(array $possibleItemCountPerPage)
    {
        $this->possibleItemCountPerPage = $possibleItemCountPerPage;
        return $this;
    }

    /**
     * Sets the current page number.
     *
     * @param  int $pageNumber Page number
     * @return Paginator $this
     */
    public function setCurrentPageNumber($pageNumber)
    {
        $adapter = $this->getAdapter();
        if ($adapter instanceof PaginatorAdapterInterface) {
            $offset = $this->getItemCountPerPage() * ($pageNumber - 1);
            $adapter->setOffset($offset);
        }
        return parent::setCurrentPageNumber($pageNumber);
    }

    /**
     * Sets the number of items per page.
     *
     * @param  int $itemCountPerPage
     * @return Paginator $this
     */
    public function setItemCountPerPage($itemCountPerPage = -1)
    {
        $adapter = $this->getAdapter();
        if ($adapter instanceof PaginatorAdapterInterface
            && $itemCountPerPage > 1
        ) {
            $adapter->setLimit($itemCountPerPage);
        }
        return parent::setItemCountPerPage($itemCountPerPage);
    }
}
