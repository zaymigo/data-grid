<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Test\PhpUnit\TestData;

/**
 * Class TestPath
 * @package Nnx\DataGrid\Test\PhpUnit\TestData
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

    /**
     * Путь до директории модуля
     *
     * @return string
     */
    public static function getPathToModule()
    {
        return __DIR__ . '/../../../';
    }

    /**
     * Возвращает путь путь до директории в которой создаются прокси классы для сущностей доктрины
     *
     * @return string
     */
    public static function getPathToDoctrineProxyDir()
    {
        return __DIR__ . '/../../../data/test/Proxies/';
    }

    /**
     * Путь до тестового приложения для проверки фукнционала гридов
     *
     * @return string
     */
    static public function getPathToGridAppConfig()
    {
        return __DIR__ . '/GridApp/config/application.config.php';
    }
}