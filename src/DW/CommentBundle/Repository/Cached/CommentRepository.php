<?php

namespace DW\CommentBundle\Repository\Cached;

use DW\DocumentaryBundle\Entity\Documentary;
use DW\UserBundle\Entity\User;
use DW\BaseBundle\Helper\CacheHelper;
use DW\CommentBundle\Repository\CommentRepository as CommentRepositoryInterface;
use DW\CommentBundle\Repository\Doctrine\ORM\CommentRepository as CommentRepositoryDoctrine;

class CommentRepository implements CommentRepositoryInterface
{
    private $commentRepository;
    private $cacheHelper;

    public function __construct(CommentRepositoryDoctrine $commentRepository, CacheHelper $cacheHelper)
    {
        $this->commentRepository = $commentRepository;
        $this->cacheHelper = $cacheHelper;
    }

    public function findCommentById($id)
    {
        return $this->commentRepository->find($id);
    }

    public function findAll()
    {
        return $this->commentRepository->findAll();
    }

    public function findCommentsByDocumentary(Documentary $documentary)
    {
        $key = "documentary_slug_".$documentary->getSlug();
        $name = "comments";
        $comments = $this->cacheHelper->getFromCache($key, $name, "ArrayCollection<DW\DWBundle\Entity\Comment>");
        if ($comments == null) {
            $comments = $this->commentRepository->findCommentsByDocumentary($documentary);
            if ($comments != null && !empty($comments)) {
                $this->cacheHelper->saveToCache($key, $name, $comments);
            }
        }
        return $comments;
    }

    public function findParentCommentsByDocumentaryOrderedByPoints(Documentary $documentary)
    {
        return $this->commentRepository->findParentCommentsByDocumentaryOrderedByPoints($documentary);
    }

    public function findParentCommentsByDocumentaryOrderedByDate(Documentary $documentary)
    {
        return $this->commentRepository->findParentCommentsByDocumentaryOrderedByDate($documentary);
    }

    public function findCommentsByUser(User $user)
    {
        return $this->commentRepository->findCommentsByUser($user);
    }

    public function findCommentsByEmail($email)
    {
        return $this->commentRepository->findCommentsByEmail($email);
    }

    public function findCommentsByEmailWithNoUser($email)
    {
        return $this->commentRepository->findCommentsByEmailWithNoUser($email);
    }

    public function findCommentsWithUser()
    {
        return $this->commentRepository->findCommentsWithUser();
    }
    public function merge($entity)
    {
        $this->commentRepository->merge($entity);
    }

    public function save($entity, $sync = true)
    {
        $this->commentRepository->save($entity, $sync);
    }

    public function flush()
    {
        $this->commentRepository->flush();
    }
}