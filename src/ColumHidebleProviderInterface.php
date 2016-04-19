<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Ushkov Nikolai <ushkov@mte-telecom.ru>
 * Date: 18.04.16
 * Time: 14:55
 */

namespace Nnx\DataGrid;

/**
 * Interface ColumHidebleProviderInterface
 * @package Nnx\DataGrid
 */
interface ColumHidebleProviderInterface
{
    /**
     * получить коллекцию колонок таблицы скрытых/показаннах пользователем
     * @return array|\Traversable
     */
    public function getUserHiddenColums();

    /**
     * установить коллекцию колонок таблицы скрытых/показаннах пользователем
     * @param array|\Traversable $userHiddenColums
     * @return $this
     */
    public function setUserHiddenColums($userHiddenColums);
}
