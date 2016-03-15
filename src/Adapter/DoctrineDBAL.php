<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace MteGrid\Grid\Adapter;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Traversable;

/**
 * Class DoctrineDBAL 
 * @package MteGrid\Grid\Adapter
 */
class DoctrineDBAL extends AbstractAdapter implements EntityManagerAwareInterface
{
    /**
     * Запрос данных
     * @var QueryBuilder
     */
    protected $query;

    /**
     * Запрос на подсчет данных
     * @var QueryBuilder
     */
    protected $countQuery;

    /**
     * Параметры сортировки
     * @var array
     */
    protected $order;

    /**
     * @var int
     */
    protected $limit = 25;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * Alias корневой сущности
     * @var string
     */
    protected $rootAlias;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;


    /**
     * Возвращает данные для грида
     * @return array
     * @throws Exception\RuntimeException
     */
    public function getData()
    {
        if (!$this->getQuery()) {
            throw new Exception\RuntimeException('Не задан query');
        }
        $query = $this->getQuery();
        if (!$query instanceof QueryBuilder) {
            throw new Exception\RuntimeException(sprintf('Query должен наследоваться от %s', QueryBuilder::class));
        }
        if ($this->getCount()) {
            $query = clone $query;
        }
        $order = $this->getOrder();
        if ((is_array($order) || $order instanceof Traversable) && 0 !== count($order)) {
            $query->resetQueryPart('orderBy');
            foreach ($order as $orderPart) {
                if (array_key_exists('field', $orderPart) && $orderPart['field']) {
                    $query->addOrderBy($orderPart['field'],
                        array_key_exists('order', $orderPart) && $orderPart['order'] ? $orderPart['orderPart'] : null);
                }
            }
        }
        $result = $query->setMaxResults($this->getLimit())
            ->setFirstResult($this->getOffset())
            ->execute();

        return $result->fetchAll();
    }

    /**
     * @return int
     * @throws Exception\RuntimeException
     */
    public function getCount()
    {
        if ($this->count !== null) {
            $res = $this->count;
        } else {
            if (!$this->getCountQuery() && !$this->getQuery()) {
                throw new Exception\RuntimeException('Не задан query для адаптера Grid');
            } elseif (!$this->getCountQuery()) {
                $query = $this->getQuery();
                if (!$query instanceof QueryBuilder) {
                    throw new Exception\RuntimeException(sprintf('Query должен наследоваться от %s', QueryBuilder::class));
                }
                $query = clone $query;
                $this->setCountQuery($query
                    ->select('COUNT(DISTINCT ' . $this->getRootAlias() . '.id) AS total_results')
                    ->resetQueryParts(['groupBy', 'orderBy'])
                    ->setMaxResults(1)
                );
            }
            $stmt = $this->getCountQuery()->execute();
            $res = $this->count = (int)$stmt->fetchColumn(0);
        }

        return $res;
    }

    /**
     * @return mixed
     */
    public function init()
    {
    }


    /**
     * @return QueryBuilder
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param QueryBuilder $query
     * @return $this
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return array
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * [['field'=> 'u.username', 'order' => 'DESC'],['field' => 'u.create_date_time']]
     * @param array | Traversable $order
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return QueryBuilder
     */
    public function getCountQuery()
    {
        return $this->countQuery;
    }

    /**
     * @param QueryBuilder $countQuery
     * @return $this
     */
    public function setCountQuery($countQuery)
    {
        $this->countQuery = $countQuery;
        return $this;
    }

    /**
     * @return string
     */
    public function getRootAlias()
    {
        return $this->rootAlias;
    }

    /**
     * @param string $rootAlias
     * @return $this
     */
    public function setRootAlias($rootAlias)
    {
        $this->rootAlias = $rootAlias;
        return $this;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param ObjectManager|EntityManagerInterface $entityManager
     * @return $this
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }
}
