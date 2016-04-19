<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Ushkov Nikolai <ushkov@mte-telecom.ru>
 * Date: 19.04.16
 * Time: 11:16
 */

namespace Nnx\DataGrid\NavigationBar;

use Zend\Stdlib\InitializableInterface;
/**
 * Class Simple
 * @package Nnx\DataGrid\NavigationBar
 */
class Simple extends AbstractNavigationBar implements InitializableInterface
{
    /**
     * Инициализация
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     * @throws \Nnx\DataGrid\Button\Exception\InvalidButtonException
     */
    public function init()
    {
        $options = $this->getOptions();
        if (!empty($options['standartButtons']) && is_array($options['standartButtons'])) {
            foreach ($options['standartButtons'] as $button) {
                if (!is_array($button)) {
                    $button = ['type' => $button];
                }
                $this->add($button);
            }
        }
    }
}
