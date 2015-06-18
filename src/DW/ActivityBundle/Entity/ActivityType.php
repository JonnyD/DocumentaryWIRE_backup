<?php

namespace DW\ActivityBundle\Entity;

abstract class ActivityType
{
    const LIKE = "like";
    const COMMENT = "comment";
    const FOLLOW = "follow";
    const JOINED = "joined";
    const ADDED = "added";
}