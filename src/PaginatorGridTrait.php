<?php
/**
 * @year 2016
 * @author Lobanov Aleksandr <lobanov@mte-telecom.ru>
 */

namespace Nnx\DataGrid;

use Nnx\DataGrid\Adapter\AdapterInterface;

/**
 * Class PaginatorGridTrait
 * @package Nnx\DataGrid
 */
trait PaginatorGridTrait
{

    /**
     * Возвращает адаптер с помощью которого будет осуществляться выборка данных
     * @return AdapterInterface
     */
    abstract public function getAdapter();

    /**
     * @var Paginator\PaginatorInterface
     */
    protected $paginator;

    /**
     * @return Paginator\PaginatorInterface
     * @throws Exception\InvalidArgumentException
     */
    public function getPaginator()
    {
        if ($this->paginator === null) {
            $adapter = $this->getAdapter();
            if ($adapter instanceof Adapter\PaginatorAdapterInterface) {
                $this->paginator = new Paginator\Paginator($adapter);
            } else {
                throw new Exception\InvalidArgumentException('Адаптер должен реализовывать \Zend\Paginator\Adapter\AdapterInterface');
            }
        }
        return $this->paginator;
    }

    /**
     * @param Paginator\PaginatorInterface $paginator
     * @return $this
     */
    public function setPaginator($paginator)
    {
        $this->paginator = $paginator;
        return $this;
    }
}
