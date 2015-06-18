<?php

namespace DW\VoteBundle\Repository\Doctrine\ORM;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use DW\BaseBundle\Repository\Doctrine\ORM\CustomRepository;
use DW\CommentBundle\Entity\Comment;
use DW\UserBundle\Entity\User;
use DW\VoteBundle\Repository\VoteRepository as VoteRepositoryInterface;

/**
 * VoteRepository
 */
class VoteRepository extends EntityRepository implements VoteRepositoryInterface
{
    public function findVoteByUserAndComment(User $user, Comment $comment)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT v FROM DocumentaryWIREBundle:Vote v
             WHERE v.user = :user
               AND v.comment = :comment')
            ->setParameter('user', $user)
            ->setParameter('comment', $comment);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findTopScoringUsers()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('username', 'username');
        $rsm->addScalarResult('displayName', 'displayName');
        $rsm->addScalarResult('avatar', 'avatar');
        $rsm->addScalarResult('email', 'email');
        $rsm->addScalarResult('score', 'score');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT user.username as username,
              user.displayName as displayName,
              user.avatar as avatar,
              user.email as email,
              SUM(vote.value) as score
            FROM vote vote
            INNER JOIN user on vote.user_id = user.id
            GROUP BY user.id
            ORDER BY score DESC
            LIMIT 8', $rsm);

        return $query->getArrayResult();
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