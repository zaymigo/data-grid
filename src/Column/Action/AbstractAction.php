<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Column\Action;

use MteBase\View\Helper\Url;

/**
 * Class AbstractAction
 * @package MteGrid\Grid\Column\Action
 */
abstract class AbstractAction implements ActionInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * Заголовок действия
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Ссылка на которую ведет действие
     * @var string
     */
    protected $url;

    /**
     * Роут для построения линки
     * @var array
     */
    protected $route;

    /**
     * UrlHelper для построения линки
     * @var Url
     */
    protected $urlHelper;

    /**
     * Опции для действия
     * @var array
     */
    protected $options;

    /**
     * @var callback | array
     */
    protected $validationFunction;


    public function __construct(array $options = [])
    {
        if (!array_key_exists('name', $options)) {
            throw new Exception\NameNotDefinedException(
                'Для корректной работы действий необходимо задать имя действия'
            );
        } else {
            $this->setName($options['name']);
        }
        if (array_key_exists('title', $options)) {
            $this->setTitle($options['title']);
        }
        if (array_key_exists('route', $options)) {
            $this->setRoute($options['route']);
        }
        if (array_key_exists('urlHelper', $options)) {
            $this->setUrlHelper($options['urlHelper']);
        }
        if (array_key_exists('url', $options)) {
            $this->setUrl($options['url']);
        }
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        if (!$this->url) {
            $urlHelper = $this->getUrlHelper();
            $route = $this->getRoute();
            $this->url = $urlHelper($route['routeName'], $route['routeParams'], $route['routeOptions']);
        }
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param array $route
     * @return $this
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return \MteBase\View\Helper\Url
     */
    public function getUrlHelper()
    {
        return $this->urlHelper;
    }

    /**
     * @param \MteBase\View\Helper\Url $urlHelper
     * @return $this
     */
    public function setUrlHelper($urlHelper)
    {
        $this->urlHelper = $urlHelper;
        return $this;
    }

    /**
     * @return bool
     */
    abstract public function validate();

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @return array|callable
     */
    public function getValidationFunction()
    {
        return $this->validationFunction;
    }

    /**
     * @param array|callable $validationFunction
     * @return $this
     */
    public function setValidationFunction($validationFunction)
    {
        $this->validationFunction = $validationFunction;
        return $this;
    }
}
