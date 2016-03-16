<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */
namespace MteGrid\Grid\Test\PhpUnit;

use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;
use RuntimeException;

/**
 * Test bootstrap, for setting up autoloading
 *
 * @subpackage UnitTest
 */
class Bootstrap
{
    /**
     * Настройка тестов
     *
     * @throws \RuntimeException
     */
    public static function init()
    {
        static::initAutoloader();
    }


    /**
     * Инициализация автозагрузчика
     *
     * @return void
     *
     * @throws RuntimeException
     */
    protected static function initAutoloader()
    {
        $vendorPath = static::findParentPath('vendor');

        $loader = null;
        if (is_readable($vendorPath . '/autoload.php')) {

            /** @noinspection PhpIncludeInspection */
            $loader = include $vendorPath . '/autoload.php';
        }

        if (!class_exists(AutoloaderFactory::class)) {
            $zf2Path = getenv('ZF2_PATH') ?: (defined('ZF2_PATH') ? constant('ZF2_PATH') : (is_dir($vendorPath . '/ZF2/library') ? $vendorPath . '/ZF2/library' : false));

            if (!$zf2Path) {
                throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
            }

            if (null !== $loader) {
                $loader->add('Zend', $zf2Path . '/Zend');
            } else {

                /** @noinspection PhpIncludeInspection */
                include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
            }
        }

        try {
            /** @noinspection SenselessCommaInArrayDefinitionInspection */
            AutoloaderFactory::factory([
                StandardAutoloader::class => [
                    'autoregister_zf' => true,
                    'namespaces' => [
                        'MteGrid\\Grid' => __DIR__ . '/../../src/',
                        __NAMESPACE__ => __DIR__ . '/tests/',
                        'MteGrid\\Grid\\Test\\PhpUnit\\TestData' => __DIR__ . '/data',
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            $errMsg = 'Ошибка инициации автолоадеров';
            throw new RuntimeException($errMsg, $e->getCode(), $e);
        }
    }

    /**
     * @param $path
     *
     * @return bool|string
     */
    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }
}

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);
Bootstrap::init();
