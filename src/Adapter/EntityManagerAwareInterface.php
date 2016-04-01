<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace NNX\DataGrid\Adapter;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Interface EntityManagerAwareInterface
 * @package NNX\DataGrid\Adapter
 */
interface EntityManagerAwareInterface
{
    /**
     * Возвращает EntityManager
     * @return mixed
     */
    public function getEntityManager();

    /**
     * Устанавдивает EntityManager
     * @param EntityManagerInterface $entityManager
     * @return mixed
     */
    public function setEntityManager(EntityManagerInterface $entityManager);
}
