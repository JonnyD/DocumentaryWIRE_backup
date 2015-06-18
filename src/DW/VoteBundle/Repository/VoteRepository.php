<?php

namespace DW\VoteBundle\Repository;

use DW\CommentBundle\Entity\Comment;
use DW\UserBundle\Entity\User;

interface VoteRepository
{
    public function findVoteByUserAndComment(User $user, Comment $comment);
    public function findTopScoringUsers();
    public function merge($entity);
    public function save($entity, $sync = false);
    public function flush();
}