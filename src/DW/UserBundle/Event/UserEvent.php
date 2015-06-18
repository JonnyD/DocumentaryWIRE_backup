<?php

namespace DW\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use DW\UserBundle\Entity\User;

class UserEvent extends Event
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
}