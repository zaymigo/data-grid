<?php
/**
 * @author: Deller <r.malashin@zaymigo.com>
 * @copyright Copyright (c) 2017, Zaymigo
 */

namespace Nnx\DataGrid\Middleware;


use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use function MongoDB\is_string_array;
use Nnx\DataGrid\Adapter\DoctrineDBAL;
use Nnx\DataGrid\Column\ColumnInterface;
use Nnx\DataGrid\Condition\Conditions;
use Nnx\DataGrid\Condition\SimpleCondition;
use Nnx\DataGrid\GridInterface;
use Nnx\DataGrid\PaginatorGridInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Stdlib\Parameters;

/**
 * Class DataMiddleware
 * @package Nnx\DataGrid
 */
class DataMiddleware implements MiddlewareInterface
{
    /**
     * @var PaginatorGridInterface | GridInterface
     */
    protected $grid;

    /**
     * DataMiddlewareFactory constructor.
     * @param GridInterface|PaginatorGridInterface $grid
     */
    public function __construct($grid)
    {
        $this->setGrid($grid);
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $queryParams = new Parameters($request->getQueryParams());
        $orderField = $queryParams->get('sidx', null);
        if(preg_match('/conditions\[(.*)\]/i', $orderField, $match)) {
            $orderField = $match[1];
        }
        $orderType = $queryParams->get('sord', null);
        $rowsOnPage = $queryParams->get('rows', 100);
        $pageNumber = $queryParams->get('page', 1);
        $conditions = $queryParams->get('conditions', []);
        /** @var GridInterface $grid */
        $grid = $this->getGrid();

        $adapter = $grid->getAdapter();
        if ($grid instanceof PaginatorGridInterface) {
            $grid->getPaginator()->setItemCountPerPage($rowsOnPage);
            $grid->getPaginator()->setCurrentPageNumber($pageNumber);
        }
        if ($adapter instanceof DoctrineDBAL) {
            if ($orderField && $orderType) {
                $adapter->setOrder([
                    [
                        'field' => $orderField,
                        'order' => $orderType
                    ]
                ]);
            }
        }
//TODO поджумать как закрыть дырку
        $conditionsObj = new Conditions();
        foreach ($conditions as $k=>$cond) {
            if (is_array($cond)) {
                $condition = $this->getCondition($cond);
                $conditionsObj[] = $condition;
            } else {
                $conditionsObj[] = $this->getCondition(['key' => $k, 'value' => $cond]);
            }
        }
        $grid->setConditions($conditionsObj);
        $data = $this->getPreparedData();
        return new JsonResponse($data);
    }

    /**
     * @param array $conditionData
     * @return SimpleCondition
     * @throws Exception\InvalidConditionNameException
     * @throws Exception\InvalidConditionValueException
     */
    protected function getCondition(array $conditionData)
    {
        if (!array_key_exists('key', $conditionData) || !$conditionData['key']) {
            throw new Exception\InvalidConditionNameException('Не задано имя условия для выборки');
        }

        if (!array_key_exists('value', $conditionData)) {
            throw new Exception\InvalidConditionValueException('Не передано значение для условия выборки.');
        }

        $conditionType = SimpleCondition::CRITERIA_TYPE_EQUAL;
        return new SimpleCondition($conditionData['key'], $conditionType, $conditionData['value']);
    }


    /**
     * @return GridInterface|PaginatorGridInterface
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * @param GridInterface|PaginatorGridInterface $grid
     * @return $this
     */
    public function setGrid($grid)
    {
        $this->grid = $grid;
        return $this;
    }

    /**
     * @return object
     */
    public function getPreparedData()
    {

        $data = $this->getGrid()->getRowset();
        $columns = $this->getGrid()->getColumns();
        $rows = [];
        if (count($data) !== 0) {
            foreach ($data as $item) {
                /** @var \NNX\DataGrid\Row $item */
                $item = $item->getData();
//                if (count($this->collapsedRows) && isset($item['is_leaf']) && isset($item['id']) && !$item['is_leaf']) {
//                    if (!in_array($item['id'], $this->collapsedRows)) {
//                        $item['expanded'] = true;
//                    }
//                }
                /**
                 * @var string $name
                 * @var \NNX\DataGrid\Column\ColumnInterface $column
                 */
                foreach ($columns as $name => $column) {
                    if ($column instanceof \NNX\DataGrid\Column\ActionAwareInterface) {
                        $item[$column->getName()] = '';
                        $actions = $column->getActions();
                        /** @var \NNX\DataGrid\Column\Action\ActionInterface $action */
                        foreach ($actions as $action) {
                            $keys = array_keys($action->getAttributes());
                            $attributes = implode(' ', array_map(function ($k, $v) {
                                return $k . '="' . $v . '"';
                            }, (count($keys) !== 0 ? $keys : []), $action->getAttributes()));
                            if ($action instanceof \NNX\DataGrid\RowDataAwareInterface) {
                                $action->setRowData($item);
                            }
                            if ($action->validate()) {
                                $item[$column->getName()] .= '<a href="'
                                    . $action->getUrl()
                                    . '" '
                                    . $attributes
                                    . '>'
                                    . ($action->getTitle())
                                    . '</a>';
                            }
                        }
                    }
                }
                $rows[] = $item;
            }
        }
        $total = 0;
        $page = 1;


        if ($this->getGrid() instanceof \Nnx\DataGrid\PaginatorGridInterface) {
            /** @var \Nnx\DataGrid\PaginatorGridInterface $grid */
            $paginator = $this->getGrid()->getPaginator();
            $total = $paginator->count();
            $page = $paginator->getCurrentPageNumber();
        }


        return (object)[
            'rows' => $rows,
            'total' => $total,
            'page' => $page,
            'records' => $this->getGrid()->getAdapter()->getCount()
        ];
    }
}