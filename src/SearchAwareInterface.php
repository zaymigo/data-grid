<?php
/**
 * @author: Deller <r.malashin@zaymigo.com>
 * @copyright Copyright (c) 2017, Zaymigo
 */

namespace Nnx\DataGrid;

/**
 * Interface SearchInterface
 * @package Nnx\DataGrid
 */
interface SearchAwareInterface
{
    /**
     * Устанавливает параметры поиска
     * @param array|null $options
     * @return $this
     */
    public function setSearchOptions(array $options = []);

    /**
     * Возвращает опции поиска для грида
     * @return array
     */
    public function getSearchOptions() : array;
}