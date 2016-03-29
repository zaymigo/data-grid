<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Column;

use MteGrid\Grid\Column\Exception\InvalidColumnException;
use MteGrid\Grid\Column\Exception\InvalidNameException;
use MteGrid\Grid\Column\Exception\InvalidSpecificationException;
use MteGrid\Grid\FactoryInterface;
use MteGrid\Grid\Mutator\MutatorInterface;
use Traversable;
use Zend\Http\Header\HeaderInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use MteGrid\Grid\Mutator\Factory as MutatorFactory;


/**
 * Class Factory
 * @package MteGrid\Grid\Column
 */
final class Factory implements FactoryInterface, GridColumnPluginManagerAwareInterface
{
    use GridColumnPluginManagerAwareTrait;

    /**
     * @param array | Traversable $spec
     * @throws InvalidColumnException
     * @throws InvalidNameException
     * @throws InvalidSpecificationException
     */
    protected function validate($spec)
    {
        if (!is_array($spec) && !$spec instanceof Traversable) {
            throw new InvalidSpecificationException(
                sprintf('Передана некорректная спецификация для создания колонки. Ожидается array или %s, прищел: %s',
                    Traversable::class,
                    gettype($spec)
                    )
            );
        }
        if (!array_key_exists('type', $spec) || !$spec['type']) {
            throw new InvalidColumnException('Не передан тип создаваемого столбца.');
        }
        if (!array_key_exists('name', $spec) || !$spec['name']) {
            throw new InvalidNameException('Не задано имя для колонки.');
        }
    }

    /**
     * Возвращщает набор мутаторов
     * @param array $spec
     * @return array
     */
    protected function getMutators($spec)
    {
        $mutators = [];
        if (array_key_exists('mutators', $spec) && $spec['mutators']) {
            $mutatorFactory = new MutatorFactory($this->getColumnPluginManager()->getServiceLocator());
            foreach ($spec['mutators'] as $mutator) {
                if (!$mutator instanceof MutatorInterface) {
                    $mutator = $mutatorFactory->create($mutator);
                }
                $mutators[] = $mutator;
            }
        }
        return $mutators;
    }

    /**
     * @param ColumnInterface $column
     * @param array | Traversable $spec
     * @return array | Traversable
     */
    protected function prepareMutatorsSpecification(ColumnInterface $column, $spec)
    {
        $mutatorsNames = $column->getInvokableMutators();
        $mutatorsOptions = [];
        if (count($mutatorsNames)) {
            if (array_key_exists('options', $spec)
                && is_array($spec['options'])
                && array_key_exists('mutatorsOptions', $spec['options'])
            ) {
                $mutatorsOptions = $spec['options']['mutatorsOptions'];
            }
            foreach ($mutatorsNames as $k => $mutator) {
                $spec['mutators'][] = [
                    'type' => $mutator,
                    'options' => array_key_exists($k, $mutatorsOptions) ? $mutatorsOptions[$k] : []
                ];
            }
            unset($spec['options']['mutatorsOptions']);
        }

        return $spec;
    }

    /**
     * Метод фабрики создающий непосредственно колонку
     * @param array | Traversable $spec
     * @return ColumnInterface
     * @throws InvalidColumnException
     * @throws InvalidSpecificationException
     * @throws ServiceNotFoundException
     * @throws ServiceNotCreatedException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \MteGrid\Grid\Column\Exception\InvalidNameException
     * @throws \MteGrid\Grid\Column\Header\Exception\NoValidSpecificationException
     * @throws \MteGrid\Grid\Column\Header\Exception\NoValidTemplateException
     */
    public function create($spec)
    {
        $this->validate($spec);
        /** @var ColumnInterface $column */
        $column = $this->getColumnPluginManager()->get($spec['type'], $spec);
        if (array_key_exists('header', $spec)
            && $spec['header']
        ) {
            if (!$spec['header'] instanceof HeaderInterface) {
                $headerFactory = new Header\Factory();
                $header = $headerFactory->create($spec['header']);
            } else {
                $header = $spec['header'];
            }
            $column->setHeader($header);
        }
        $spec = $this->prepareMutatorsSpecification($column, $spec);
        $column->setMutators($this->getMutators($spec));

        return $column;
    }
}
