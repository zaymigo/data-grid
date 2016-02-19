<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Adapter;

/**
 * Interface AdapterInterface
 * @package MteGrid\Grid\Adapter
 */
interface AdapterInterface
{
    /**
     * @return array
     */
    public function getData();

    /**
     * @return int
     */
    public function getCount();
}
