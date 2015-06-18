<?php

namespace DW\CommentBundle\Service;

use DW\CommentBundle\Entity\Comment;
use DW\DocumentaryBundle\Entity\Documentary;
use DW\UserBundle\Entity\User;
use DW\CommentBundle\Event\CommentEvent;
use DW\CommentBundle\Event\CommentEvents;
use DW\CommentBundle\Repository\CommentRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CommentService
{
    private $commentRepository;
    private $eventDispatcher;
	
	public function __construct(CommentRepository $commentRepository,
                                EventDispatcherInterface $eventDispatcher)
	{
        $this->commentRepository = $commentRepository;
        $this->eventDispatcher = $eventDispatcher;
	}

    public function getCommentById($id)
    {
        return $this->commentRepository->findCommentById($id);
    }

    public function addComment(Comment $comment)
    {
        $this->commentRepository->save($comment);

        $this->eventDispatcher->dispatch(
            CommentEvents::DOCUMENTARY_COMMENT_ADDED,
            new CommentEvent($comment)
        );
    }

    public function getAllComments()
    {
        return $this->commentRepository->findAll();
    }

    public function getCommentsByDocumentary(Documentary $documentary)
    {
        return $this->commentRepository->findCommentsByDocumentary($documentary);
    }

    public function getParentCommentsByDocumentaryOrderedByDate(Documentary $documentary)
    {
        return $this->commentRepository->findParentCommentsByDocumentaryOrderedByDate($documentary);
    }

    public function getParentCommentsByDocumentaryOrderedByPoints(Documentary $documentary)
    {
        return $this->commentRepository->findParentCommentsByDocumentaryOrderedByPoints($documentary);
    }

    public function addUserToComments(User $user)
    {
        $comments = $this->commentRepository->findCommentsByEmailWithNoUser($user->getEmail());
        foreach ($comments as $comment) {
            $comment->setUser($user);
            $this->commentRepository->save($comment, false);
        }
        $this->commentRepository->flush();
    }

    public function getCommentsWithUser()
    {
        return $this->commentRepository->findCommentsWithUser();
    }
}