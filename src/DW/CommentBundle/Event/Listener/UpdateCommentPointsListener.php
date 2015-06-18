<?php

namespace DW\DWBundle\EventListener;

use DW\BaseBundle\Cache\ActivityCache;
use DW\BaseBundle\Cache\CategoryCache;
use DW\DocumentaryBundle\Cache\DocumentaryCache;
use DW\BaseBundle\Cache\LikeCache;
use DW\BaseBundle\Cache\UserCache;
use DW\BaseBundle\Cache\YearCache;
use DW\DWBundle\Event\ActivityEvent;
use DW\DWBundle\Event\CommentEvent;
use DW\DWBundle\Event\CommentEvents;
use DW\DWBundle\Event\DocumentaryEvent;
use DW\DWBundle\Event\DocumentaryEvents;
use DW\DWBundle\Event\LikeEvent;
use DW\DWBundle\Event\LikeEvents;
use DW\DWBundle\Event\UserEvent;
use DW\DWBundle\Event\UserEvents;
use DW\VoteBundle\Event\VoteEvent;
use DW\VoteBundle\Event\VoteEvents;
use DW\BaseBundle\Helper\CacheHelper;
use DW\CommentBundle\Manager\CommentManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommentPointsListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(
            VoteEvents::VOTE_CREATED => "onVoteCreated",
            VoteEvents::VOTE_UPDATED => "onVoteUpdated"
        );
    }

    public function __construct()
    {

    }

    public function onVoteCreated(VoteEvent $voteEvent)
    {
        $vote = $voteEvent->getVote();
        $comment = $vote->getComment();


    }

    public function onVoteUpdated(VoteEvent $voteEvent)
    {

    }
}