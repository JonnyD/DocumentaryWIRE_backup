<?php

namespace DW\UserBundle\Repository\Doctrine\ORM;

use Doctrine\ORM\EntityRepository;

/**
 * RoleRepository
 */
class RoleRepository extends EntityRepository
{
    public function merge($entity)
    {
        $this->getEntityManager()->merge($entity);
    }

    public function save($entity, $sync = false)
    {
        $this->getEntityManager()->persist($entity);
        if ($sync) {
            $this->flush();
        }
    }

    public function flush()
    {
        $this->getEntityManager()->flush();
    }
}