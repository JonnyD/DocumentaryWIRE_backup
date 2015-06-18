<?php

namespace DW\DWBundle\EventListener;

use DW\ActivityBundle\Entity\ActivityType;
use DW\ActivityBundle\Entity\ActivityComponent;
use DW\CommentBundle\Event\CommentEvent;
use DW\CommentBundle\Event\CommentEvents;
use DW\DocumentaryBundle\Event\DocumentaryEvent;
use DW\DocumentaryBundle\Event\DocumentaryEvents;
use DW\LikeBundle\Event\LikeEvent;
use DW\LikeBundle\Event\LikeEvents;
use DW\UserBundle\Event\UserEvent;
use DW\UserBundle\Event\UserEvents;
use DW\ActivityBundle\Service\ActivityService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddActivityListener implements EventSubscriberInterface
{
    private $activityManager;

    public static function getSubscribedEvents()
    {
        return array(
            DocumentaryEvents::NEW_DOCUMENTARY_ADDED => "onDocumentaryAdded",
            LikeEvents::DOCUMENTARY_LIKED => "onDocumentaryLiked",
            UserEvents::USER_CONFIRMED => "onUserConfirmed",
            CommentEvents::DOCUMENTARY_COMMENT_ADDED => "onCommentAdded"
        );
    }

    public function __construct(ActivityService $activityManager)
    {
        $this->activityManager = $activityManager;
    }

    public function onDocumentaryAdded(DocumentaryEvent $documentaryEvent)
    {
        $documentary = $documentaryEvent->getDocumentary();

        $data = array(
            "documentaryId" => $documentary->getId(),
            "documentaryTitle" => $documentary->getTitle(),
            "documentaryExcerpt" => $documentary->getExcerpt(),
            "documentaryThumbnail" => $documentary->getThumbnail(),
            "documentarySlug" => $documentary->getSlug());

        $this->activityManager->addActivity($documentary->getAddedBy(), $documentary->getId(), ActivityType::ADDED,
            ActivityComponent::DOCUMENTARY, $data);
    }

    public function onDocumentaryLiked(LikeEvent $likeEvent)
    {
        $like = $likeEvent->getLike();
        $user = $like->getUser();
        $documentary = $like->getDocumentary();

        $activity = $this->activityManager->getActivity($user, $documentary->getId(),
            ActivityType::LIKE, ActivityComponent::DOCUMENTARY);

        if ($activity) {
            $activity->setCreated(new \DateTime());
            $this->activityManager->persist($activity);
        } else {
            $data = array(
                "documentaryId" => $documentary->getId(),
                "documentaryTitle" => $documentary->getTitle(),
                "documentaryExcerpt" => $documentary->getExcerpt(),
                "documentaryThumbnail" => $documentary->getThumbnail(),
                "documentarySlug" => $documentary->getSlug());

            $this->activityManager->addActivity($user, $documentary->getId(),
                ActivityType::LIKE, ActivityComponent::DOCUMENTARY, $data);
        }
    }

    public function onUserConfirmed(UserEvent $userEvent)
    {
        $user = $userEvent->getUser();
        $this->activityManager->addActivity($user, $user->getId(), ActivityType::JOINED,
            ActivityComponent::USER, array());
    }

    public function onCommentAdded(CommentEvent $commentEvent)
    {
        $comment = $commentEvent->getComment();
        $documentary = $comment->getDocumentary();
        $user = $comment->getUser();

        $data = array(
            "documentaryId" => $documentary->getId(),
            "documentaryTitle" => $documentary->getTitle(),
            "documentaryThumbnail" => $documentary->getThumbnail(),
            "documentarySlug" => $documentary->getSlug(),
            "comment" => $comment->getComment());

        $this->activityManager->addActivity($user, $comment->getId(),
            ActivityType::COMMENT, ActivityComponent::DOCUMENTARY, $data);
    }
}