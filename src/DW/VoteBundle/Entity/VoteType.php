<?php

namespace DW\VoteBundle\Entity;

abstract class VoteType
{
    const UPVOTE = "upvote";
    const DOWNVOTE = "downvote";
    const NEUTRAL = "neutral";
}