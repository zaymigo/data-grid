<?php
/**
 * @author Deller myogogo@gmail.com
 */

namespace MteGrid\Grid\Column;

use MteGrid\Grid\Column\Action\ActionInterface;
use Zend\View\Helper\Url;

/**
 * Class Action
 * @package MteGrid\Grid\Column
 */
class Action extends AbstractColumn implements ActionAwareInterface
{
    /**
     * @var array
     */
    protected $actions = [];

    /**
     * @var Url
     */
    protected $urlHelper;


    public function __construct(array $options = [])
    {
        parent::__construct($options);
        if (array_key_exists('actions', $options)) {
            if (!is_array($options['actions'])) {
                throw new Exception\InvalidArgumentException(
                    'Действия для колонки действий должны приходить в виде массива'
                );
            }
            $this->setActions($options['actions']);
            unset($options['actions']);
        }
        if (array_key_exists('urlHelper', $options)) {
            $this->setUrlHelper($options['urlHelper']);
        }
        $this->setOptions($options);
    }

    /**
     * Возвращает набор действий в колонке
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Устанавливает набор действий в колонку
     * @param array $actions
     * @return $this
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
        return $this;
    }

    /**
     * Добавляет действие в колонку
     * @param ActionInterface|array $action
     * @return $this
     */
    public function addAction($action)
    {
        if (!$action instanceof ActionInterface && is_array($action)) {
            $actionFactory = new Action\Factory();
            $action['urlHelper'] = $this->getUrlHelper();
            $action = $actionFactory->create($action);
        }
        $this->actions[$action->getName()] = $action;
        return $this;
    }

    /**
     * @return Url
     */
    public function getUrlHelper()
    {
        return $this->urlHelper;
    }

    /**
     * @param Url $urlHelper
     * @return $this
     */
    public function setUrlHelper($urlHelper)
    {
        $this->urlHelper = $urlHelper;
        return $this;
    }
}
