<?php

namespace DW\LikeBundle\Repository\Doctrine\ORM;

use Doctrine\ORM\EntityRepository;
use DW\DocumentaryBundle\Entity\Documentary;
use DW\UserBundle\Entity\User;
use DW\LikeBundle\Repository\LikeRepository as LikeRepositoryInterface;

/**
 * LikeRepository
 */
class LikeRepository extends EntityRepository implements LikeRepositoryInterface
{
    public function findAllLikes()
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT l, d FROM DocumentaryWIREBundle:Like l');

        return $query->getResult();
    }

    public function deleteLikeByUserAndDocumentary(User $user, Documentary $documentary)
    {
        $query = $this->getEntityManager()->createQuery(
            'DELETE FROM DocumentaryWIREBundle:Like l
              WHERE l.user = :user
                AND l.documentary = :documentary')
            ->setParameter('user', $user)
            ->setParameter('documentary', $documentary);

        $query->execute();
    }

    public function findLikesByUser(User $user)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT l, d FROM DocumentaryWIREBundle:Like l
            JOIN l.documentary d
            WHERE l.user = :user
            ORDER BY l.created DESC')
            ->setParameter('user', $user);

        return $query->getResult();
    }

    public function findLikeByUserAndDocumentary(User $user, Documentary $documentary)
    {
        $query = $this->getEntityManager()->createQuery('
            SELECT l FROM DocumentaryWIREBundle:Like l
            WHERE l.user = :user
              AND l.documentary = :documentary')
            ->setParameter('user', $user)
            ->setParameter('documentary', $documentary);

        return $query->getSingleResult();
    }

    public function findLikesByDocumentary(Documentary $documentary)
    {
        $query = $this->getEntityManager()->createQuery('
            SELECT l FROM DocumentaryWIREBundle:Like l
            WHERE l.documentary = :documentary')
            ->setParameter('documentary', $documentary);

        return $query->getResult();
    }

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