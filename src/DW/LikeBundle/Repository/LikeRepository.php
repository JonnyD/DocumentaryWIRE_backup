<?php

namespace DW\LikeBundle\Repository;

use DW\DocumentaryBundle\Entity\Documentary;
use DW\UserBundle\Entity\User;

interface LikeRepository
{
    public function findAllLikes();
    public function deleteLikeByUserAndDocumentary(User $user, Documentary $documentary);
    public function findLikesByUser(User $user);
    public function findLikeByUserAndDocumentary(User $user, Documentary $documentary);
    public function findLikesByDocumentary(Documentary $documentary);
    public function merge($entity);
    public function save($entity, $sync = false);
    public function flush();
}