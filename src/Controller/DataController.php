<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Controller;

use Nnx\DataGrid\Adapter\DoctrineDBAL;
use Nnx\DataGrid\Condition\Conditions;
use Nnx\DataGrid\GridPluginManager;
use Zend\Mvc\Controller\AbstractActionController;
use Nnx\DataGrid\GridInterface;
use Nnx\DataGrid\Condition\SimpleCondition;
use Zend\View\Model\ViewModel;

/**
 * Class DataController
 * @package Nnx\DataGrid\Controller
 * @deprecated use Nnx\DataGrid\Middleware\DataMiddleware
 */
class DataController extends AbstractActionController
{
    /**
     * @var GridPluginManager
     */
    protected $gridManager;

    public function __construct(GridPluginManager $gridManager)
    {
        $this->setGridManager($gridManager);
    }

    /**
     * Возвращает данные для таблиц
     * @return ViewModel
     * @throws Exception\InvalidConditionNameException
     * @throws Exception\InvalidConditionValueException
     * @throws Exception\InvalidGridNameException
     */
    public function getAction()
    {
        /** @var \ZF\ContentNegotiation\Request $request */
        $request = $this->getRequest();
        $gridName = $request->getQuery('grid');
        $limit = (int)$request->getQuery('limit', 25);
        $offset = (int)$request->getQuery('offset', 0);
        $orderField = $request->getQuery('sidx', null);
        $orderType = $request->getQuery('sorder', null);
        $collapsedRows = is_array($request->getPost('collapsedRows')) ? $request->getPost('collapsedRows') : [];
        /**
         * $conditions => [
         *      [
         *          'key' => $name,
         *          'value' => $value
         *      ]
         * ];
         */
        $conditions = $request->getQuery('conditions', []);
        if (!$gridName) {
            throw new Exception\InvalidGridNameException('Не задано имя таблицы для получения данных');
        }
        /** @var GridInterface $grid */
        $grid = $this->getGridManager()->get('grids.' . $gridName);

        $adapter = $grid->getAdapter();
        if ($adapter instanceof DoctrineDBAL) {
            $adapter->setLimit($limit);
            $adapter->setOffset($offset);
            if ($orderField && $orderType) {
                $adapter->setOrder([
                    [
                        'field' => $orderField,
                        'order' => $orderType
                    ]
                ]);
            }
        }

        $conditionsObj = new Conditions();
        foreach ($conditions as $cond) {
            if (is_array($cond)) {
                $condition = $this->getCondition($cond);
                $conditionsObj[] = $condition;
            }
        }
        $grid->setConditions($conditionsObj);
        $result = new ViewModel(['grid' => $grid, 'collapsedRows' => $collapsedRows]);
        $result->setTerminal(true);
        return $result;
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
     * @return GridPluginManager
     */
    public function getGridManager(): GridPluginManager
    {
        return $this->gridManager;
    }

    /**
     * @param GridPluginManager $gridManager
     * @return $this
     */
    public function setGridManager(GridPluginManager $gridManager)
    {
        $this->gridManager = $gridManager;
        return $this;
    }
}
