<?php

namespace DW\VoteBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use DW\VoteBundle\Entity\Vote;

class VoteEvent extends Event
{
    private $vote;

    public function __construct(Vote $vote)
    {
        $this->vote = $vote;
    }

    public function getVote()
    {
        return $this->vote;
    }
}