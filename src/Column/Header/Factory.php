<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Column\Header;
use MteGrid\Grid\Column\Header\Exception\NoValidTypeException;
use Traversable;
/**
 * Class Factory
 * @package MteGrid\Grid\Column\Header
 */
class Factory
{

    /**
     * @param array | Traversable $spec
     * @throws NoValidTypeException
     */
    public function create($spec)
    {
        if(!array_key_exists('type', $spec)){
            throw new NoValidTypeException(sprintf('Не задан тип создаваемого заголовка для таблицы.'));
        }
    }
}
