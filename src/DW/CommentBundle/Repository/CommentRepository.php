<?php

namespace DW\CommentBundle\Repository;

use DW\DocumentaryBundle\Entity\Documentary;
use DW\UserBundle\Entity\User;

interface CommentRepository
{
    public function findCommentById($id);
    public function findAll();
    public function findCommentsByDocumentary(Documentary $documentary);
    public function findParentCommentsByDocumentaryOrderedByPoints(Documentary $documentary);
    public function findParentCommentsByDocumentaryOrderedByDate(Documentary $documentary);
    public function findCommentsByUser(User $user);
    public function findCommentsByEmail($email);
    public function findCommentsByEmailWithNoUser($email);
    public function findCommentsWithUser();
    public function merge($entity);
    public function save($entity, $sync = false);
    public function flush();
}