<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace NNX\DataGrid\Column;

use NNX\DataGrid\Column\Exception\InvalidColumnException;
use NNX\DataGrid\Column\Exception\InvalidNameException;
use NNX\DataGrid\Column\Exception\InvalidSpecificationException;
use NNX\DataGrid\FactoryInterface;
use NNX\DataGrid\Mutator\MutatorInterface;
use Traversable;
use Zend\Http\Header\HeaderInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use NNX\DataGrid\Mutator\Factory as MutatorFactory;


/**
 * Class Factory
 * @package NNX\DataGrid\Column
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
     * Метод осуществляет подготовку данных предустановленных мутаторов по умолчанию.
     * Преобразует массив данных для предустановленных мутаторов в общий формат.
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
     * @throws \NNX\DataGrid\Column\Exception\InvalidNameException
     * @throws \NNX\DataGrid\Column\Header\Exception\NoValidSpecificationException
     * @throws \NNX\DataGrid\Column\Header\Exception\NoValidTemplateException
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
