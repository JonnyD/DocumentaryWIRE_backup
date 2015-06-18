<?php

namespace DW\CommentBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use DW\CommentBundle\Entity\Comment;

class CommentEvent extends Event
{
    private $comment;

    public function __construct(comment $comment)
    {
        $this->comment = $comment;
    }

    public function getComment()
    {
        return $this->comment;
    }
}