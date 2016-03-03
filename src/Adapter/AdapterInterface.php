<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Adapter;

use MteGrid\Grid\Condition\Conditions;

/**
 * Interface AdapterInterface
 * @package MteGrid\Grid\Adapter
 */
interface AdapterInterface
{
    /**
     * @return array | ArrayCollection
     */
    public function getData();

    /**
     * @return int
     */
    public function getCount();

    /**
     * @param Conditions $conditions
     * @return mixed
     */
    public function setConditions($conditions);

    /**
     * @return Conditions
     */
    public function getConditions();
}
