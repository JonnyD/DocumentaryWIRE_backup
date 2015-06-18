<?php

namespace DW\VoteBundle\Repository\Cached;

use Doctrine\ORM\EntityRepository;
use DW\BaseBundle\Repository\Cached\CustomRepository;
use DW\CommentBundle\Entity\Comment;
use DW\UserBundle\Entity\User;
use DW\BaseBundle\Helper\CacheHelper;
use DW\VoteBundle\Repository\VoteRepository as VoteRepositoryInterface;
use DW\VoteBundle\Repository\Doctrine\ORM\VoteRepository as VoteRepositoryDoctrine;

class VoteRepository extends EntityRepository implements VoteRepositoryInterface
{
    private $voteRepository;
    private $cacheHelper;

    public function __construct(VoteRepositoryDoctrine $voteRepository, CacheHelper $cacheHelper)
    {
        $this->voteRepository = $voteRepository;
        $this->cacheHelper = $cacheHelper;
    }

    public function findVoteByUserAndComment(User $user, Comment $comment)
    {
        return $this->voteRepository->findVoteByUserAndComment($user, $comment);
    }

    public function findTopScoringUsers()
    {
        return $this->voteRepository->findTopScoringUsers();
    }

    public function merge($entity)
    {
        $this->voteRepository->merge($entity);
    }

    public function save($entity, $sync = true)
    {
        $this->voteRepository->save($entity, $sync);
    }

    public function flush()
    {
        $this->voteRepository->flush();
    }
}