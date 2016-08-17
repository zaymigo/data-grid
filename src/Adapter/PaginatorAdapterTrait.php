<?php
/**
 * @year 2016
 * @author Lobanov Aleksandr <lobanov@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Adapter;

/**
 * Class PaginatorAdapterTrait
 * @package Nnx\DataGrid\Adapter
 */
trait PaginatorAdapterTrait
{
    /**
     * @return int
     */
    abstract public function getCount();

    /**
     * @return array
     */
    abstract public function getData();

    /**
     * @param int $offset
     * @return $this
     */
    abstract public function setOffset($offset);

    /**
     * @param int $limit
     * @return $this
     */
    abstract public function setLimit($limit);

    /**
     * @return int
     */
    public function count()
    {
        return $this->getCount();
    }

    /**
     * Returns a collection of items for a page.
     *
     * @param  int $offset Page offset
     * @param  int $itemCountPerPage Number of items per page
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $this->setOffset($offset);
        $this->setLimit($itemCountPerPage);
        return $this->getData();
    }
}
