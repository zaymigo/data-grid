<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Nnx\DataGrid\Column;

use Nnx\DataGrid\Column\Exception\InvalidColumnException;
use Nnx\DataGrid\Column\Exception\InvalidNameException;
use Nnx\DataGrid\Column\Exception\InvalidSpecificationException;
use Zend\ServiceManager\FactoryInterface;
use Nnx\DataGrid\Mutator\MutatorInterface;
use Traversable;
use Zend\Http\Header\HeaderInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use ReflectionClass;
use Nnx\DataGrid\Mutator\Exception\RuntimeException;


/**
 * Class Factory
 * @package Nnx\DataGrid\Column
 */
class Factory implements MutableCreationOptionsInterface, FactoryInterface
{
    use GridColumnPluginManagerAwareTrait;
    use MutableCreationOptionsTrait;


    /**
     * Конструктор класса
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->setCreationOptions($options);
    }

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
            $mutatorPluginManager = $this->getColumnPluginManager()->getServiceLocator()->get('GridMutatorManager');
            foreach ($spec['mutators'] as $mutator) {
                if (!$mutator instanceof MutatorInterface) {
                    if (!array_key_exists('type', $mutator) || !$mutator['type']) {
                        throw new RuntimeException('Для создания экземпляра мутатора должен быть передан его type');
                    }
                    $mutator['options'] = array_key_exists('options', $mutator) ? $mutator['options'] : [];
                    $mutator = $mutatorPluginManager->get($mutator['type'], $mutator['options']);
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

    protected function createHeader($spec)
    {
        $header = null;
        if (array_key_exists('header', $spec)
            && $spec['header']
        ) {
            if (!$spec['header'] instanceof HeaderInterface) {
                /** @var Header\Factory $headerFactory */
                $headerFactory = $this->getColumnPluginManager()->getServiceLocator()->get(Header\Factory::class);
                $header = $headerFactory->create($spec['header']);
            } else {
                $header = $spec['header'];
            }
        }
        return $header;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $spec = $this->getCreationOptions();
        $this->setColumnPluginManager($serviceLocator);
        $this->validate($spec);
        $className = __NAMESPACE__ . '\\' . ucfirst($spec['type']);
        $reflectionColumn = new ReflectionClass($className);
        if (!$reflectionColumn->isInstantiable()) {
            throw new Exception\RuntimeException(sprintf('Класс %s не найден', $className));
        }
        unset($spec['columnPluginManager']);
        $column = $reflectionColumn->newInstance($spec);
        $header = $this->createHeader($spec);
        $column->setHeader($header);
        $spec = $this->prepareMutatorsSpecification($column, $spec);
        $column->setMutators($this->getMutators($spec));

        return $column;
    }
}
