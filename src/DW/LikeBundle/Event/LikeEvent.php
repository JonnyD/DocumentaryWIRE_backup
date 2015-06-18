<?php

namespace DW\LikeBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use DW\LikeBundle\Entity\Like;

class LikeEvent extends Event
{
    private $like;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }

    public function getLike()
    {
        return $this->like;
    }
}