<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Column;

use MteGrid\Grid\Column\Header\HeaderInterface;
use Traversable;

/**
 * Interface ColumnInterface
 * @package MteGrid\Grid\Column
 */
interface ColumnInterface
{
    /**
     * Устанавливает имя колонки по которому в дальнейшем будут маппиться данные
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Возвращает имя колонки
     * @return string
     */
    public function getName();

    /**
     * Устанавливает заголовок для колонки
     * @param HeaderInterface | array | Traversable $header
     * @return $this
     */
    public function setHeader($header);

    /**
     * Возвращает объект заголовка для колонки
     * @return HeaderInterface
     */
    public function getHeader();

    /**
     * Устанавливает путь до шаблона строки
     * @param string $template
     * @return $this
     */
    public function setTemplate($template);

    /**
     * Возвраащет путь до шаблона
     * @return string
     */
    public function getTemplate();

    /**
     * Опции и настройки колонки
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options = []);

    /**
     * Возвращает опции колонки
     * @return array
     */
    public function getOptions();

    /**
     * Аттрибуты колонки
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes = []);

    /**
     * Возвращает атрибуты колонки
     * @return array
     */
    public function getAttributes();
}
