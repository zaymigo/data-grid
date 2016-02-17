<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Test\PhpUnit\TestData;

/**
 * Class TestPath
 * @package MteGrid\Grid\Test\PhpUnit\TestData
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