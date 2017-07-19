<?php
/**
 * @author: Deller <r.malashin@zaymigo.com>
 * @copyright Copyright (c) 2017, Zaymigo
 */

namespace Nnx\DataGrid;


trait SearchAwareTrait
{
    /**
     * @var array
     */
    protected $searchOptions = [];

    /**
     * @return array
     */
    public function getSearchOptions(): array
    {
        return $this->searchOptions;
    }

    /**
     * @param array $searchOptions
     * @return $this
     */
    public function setSearchOptions(array $searchOptions = [])
    {
        $this->searchOptions = $searchOptions;
        return $this;
    }
}