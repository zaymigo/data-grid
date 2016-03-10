<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Adapter;

use Doctrine\Common\Collections\ArrayCollection;
use MteGrid\Grid\Condition\Conditions;
use ArrayAccess;
use Traversable;

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
     * Устанавливает набор Conditions для выборки
     * @param Conditions $conditions
     * @return mixed
     */
    public function setConditions(Conditions $conditions);

    /**
     * Возвращает набор Conditions для выборки
     * @return Conditions
     */
    public function getConditions();

    /**
     * Устанавливает опции для адаптера
     * @param array | ArrayAccess | Traversable $options
     * @return $this
     */
    public function setOptions($options);

    /**
     * Возвращает опции адаптера
     * @return array | ArrayAccess | Traversable
     */
    public function getOptions();
}