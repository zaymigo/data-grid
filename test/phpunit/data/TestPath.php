<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace NNX\DataGrid\Test\PhpUnit\TestData;

/**
 * Class TestPath
 * @package NNX\DataGrid\Test\PhpUnit\TestData
 */
final class TestPath
{

    /**
     * @return string
     */
    static public function getApplicationConfigPath()
    {
        return __DIR__ . '/application.config.php';
    }
}