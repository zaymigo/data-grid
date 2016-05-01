<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Condition;

/**
 * Class DB
 * @package Nnx\DataGrid\Condition
 */
class DB extends SimpleCondition
{
    const CRITERIA_TYPE_NOT_EQUAL = '!=';

    const CRITERIA_TYPE_IS = 'is';

    const CRITERIA_TYPE_IS_NOT = 'is not';

    const CRITERIA_TYPE_GREATER = '>';

    const CRITERIA_TYPE_LESS = '<';

    const CRITERIA_TYPE_LESS_EQUAL = '<=';

    const CRITERIA_TYPE_GREATER_EQUAL = '>=';

    const CRITERIA_TYPE_IN = 'in';

    const CRITERIA_TYPE_NOT_IN = 'not in';
}
