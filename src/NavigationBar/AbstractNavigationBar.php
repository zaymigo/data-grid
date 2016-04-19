<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Ushkov Nikolai <ushkov@mte-telecom.ru>
 * Date: 18.04.16
 * Time: 16:53
 */
namespace Nnx\DataGrid\NavigationBar;

use ArrayAccess;
use Nnx\DataGrid\Button;
use Nnx\DataGrid\Button\GridButtonPluginManagerAwareTrait;
use Nnx\DataGrid\Button\ButtonInterface;
/**
 * Class AbstractNavigationBar
 * @package Nnx\DataGrid\NavigationBar
 */
abstract class AbstractNavigationBar implements NavigationBarInterface
{
    use GridButtonPluginManagerAwareTrait;

    /**
     * Кнопки бара
     * @var array
     */
    protected $buttons = [];

    /**
     * Опции бара
     * @var array
     */
    protected $options = [];

    /**
     * @param array $options
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($options = [])
    {
        if (empty($options['buttonPluginManager'])) {
            throw new Exception\InvalidArgumentException(
                'Для корректной работы навигационного бара в конструктор необходимо передавать buttonPluginManager.'
            );
        }
        $buttonPluginManager = $options['buttonPluginManager'];
        $this->setButtonPluginManager($buttonPluginManager);
        unset($options['buttonPluginManager']);
        $this->setOptions($options);
    }


    /**
     * добавить кнопку
     * @param ButtonInterface | array | ArrayAccess $button
     * @return $this
     * @throws Button\Exception\InvalidButtonException
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function add($button)
    {
        if (is_array($button) || $button instanceof ArrayAccess) {
            if (!array_key_exists('type', $button)) {
                throw new Button\Exception\InvalidButtonException(
                    'Не передан тип создаваемой кнопки.'
                );
            }
            $button['buttonPluginManager'] = $this->getButtonPluginManager();
            if (!$this->getButtonPluginManager()->has($button['type'])) {
                throw new Exception\RuntimeException(sprintf('Кнопка с именем %s не найдена', $button['type']));
            }
            /** @var ButtonInterface $button */
            $button = $this->getButtonPluginManager()->get($button['type'], $button);
        } elseif (!$button instanceof ButtonInterface) {
            throw new Exception\InvalidArgumentException(
                sprintf('Кнопка должна быть массивом или реализовывать %s', ButtonInterface::class)
            );
        }
        $this->buttons[$button->getName()] = $button;
        return $this;
    }

    /**
     * удалить кнопку
     * @param $name
     * @return $this
     */
    public function remove($name)
    {
        unset($this->buttons[$name]);
        return $this;
    }

    /**
     * получить кнопки бара
     * @return array
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * установить кнопки бара
     * @param array $buttons
     * @return $this
     */
    public function setButtons($buttons)
    {
        $this->buttons = $buttons;
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
}
