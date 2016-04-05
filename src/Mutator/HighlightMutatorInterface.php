<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Mutator;

/**
 * Interface HighlightMutatorInterface
 * @package Nnx\DataGrid\Mutator
 */
interface HighlightMutatorInterface
{

    /**
     * Возвращает css class который установится для элемента
     * @return string
     */
    public function getHighlightCssClass();

    /**
     * Устанавливает css class который установится для элемента
     * @param string $highlightCssClass
     * @return $this
     */
    public function setHighlightCssClass($highlightCssClass);

    /**
     * Возвращает имя для элемента массива в который запишется
     * класс css стиля
     * @return string
     */
    public function getDataName();

    /**
     * Устанавливает имя для элемента массива в который запишется
     * класс css стиля
     * @param string $dataName
     * @return $this
     */
    public function setDataName($dataName);
}