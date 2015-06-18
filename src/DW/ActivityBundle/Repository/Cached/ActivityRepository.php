<?php

namespace DW\ActivityBundle\Repository\Cached;

use DW\BaseBundle\Cache\ActivityCache;
use DW\UserBundle\Entity\User;
use DW\BaseBundle\Helper\CacheHelper;
use DW\ActivityBundle\Repository\ActivityRepository as ActivityRepositoryInterface;
use DW\ActivityBundle\Repository\Doctrine\ORM\ActivityRepository as ActivityRepositoryDoctrine;

class ActivityRepository implements ActivityRepositoryInterface
{
    private $activityRepository;
    private $cacheHelper;

    public function __construct(ActivityRepositoryDoctrine $activityRepository, CacheHelper $cacheHelper)
    {
        $this->activityRepository = $activityRepository;
        $this->cacheHelper = $cacheHelper;
    }

    public function findRecentLikeActivity($limit)
    {
        $key = ActivityCache::KEY_LIKES_WIDGET;
        $name = ActivityCache::CACHE_ACTIVITY;
        $classType = "ArrayCollection<DW\DWBundle\Entity\Activity>";
        $activity = $this->cacheHelper->getFromCache($key, $name, $classType);
        if ($activity == null) {
            $activity = $this->activityRepository->findRecentLikeActivity($limit);
            $this->cacheHelper->saveToCache($key, $name, $activity);
        }
        return $activity;
    }

    public function findRecentCommentActivity($limit)
    {
        $key = ActivityCache::KEY_COMMENTS_WIDGET;
        $name = ActivityCache::CACHE_ACTIVITY;
        $activity = $this->cacheHelper->getFromCache($key, $name, "ArrayCollection<DW\DWBundle\Entity\Activity>");
        if ($activity == null) {
            $activity = $this->activityRepository->findRecentCommentActivity($limit);
            $this->cacheHelper->saveToCache($key, $name, $activity);
        }
        return $activity;
    }

    public function find($id)
    {
        return $this->activityRepository->find($id);
    }

    public function findActivityByUserAndTypeBetweenDates($user, $type, $from, $to)
    {
        return $this->activityRepository->findActivityByUserAndTypeBetweenDates($user, $type, $from, $to);
    }

    public function findActivityByTypeBetweenDates($type, $from, $to)
    {
        return $this->activityRepository->findActivityByTypeBetweenDates($type, $from, $to);
    }

    public function updateActivityByTypeBetweenDates($type, $from, $to, $groupNumber)
    {
        $this->activityRepository->updateActivityByTypeBetweenDates($type, $from, $to, $groupNumber);
    }

    public function findRecentActivity($limit)
    {
        $key = ActivityCache::KEY_ALL_WIDGET;
        $name = ActivityCache::CACHE_ACTIVITY;
        $activity = $this->cacheHelper->getFromCache($key, $name, "ArrayCollection<DW\DWBundle\Entity\Activity>");
        if ($activity == null) {
            $activity = $this->activityRepository->findRecentActivity($limit);
            $this->cacheHelper->saveToCache($key, $name, $activity);
        }
        return $activity;
    }

    public function findActivityByUser(User $user)
    {
        $key = ActivityCache::KEY_USER_ID."_".$user->getId();
        $name = ActivityCache::CACHE_ACTIVITY;
        $activity = $this->cacheHelper->getFromCache($key, $name, "ArrayCollection<DW\DWBundle\Entity\Activity>");
        if ($activity == null) {
            $activity = $this->activityRepository->findActivityByUser($user);
            $this->cacheHelper->saveToCache($key, $name, $activity);
        }
        return $activity;
    }

    public function findActivityOrderedByCreated()
    {
        $key = ActivityCache::KEY_LIST;
        $name = ActivityCache::CACHE_ACTIVITY;
        $activity = $this->cacheHelper->getFromCache($key, $name, "ArrayCollection<DW\DWBundle\Entity\Activity>");
        if ($activity == null) {
            $activity = $this->activityRepository->findActivityOrderedByCreated();
            $this->cacheHelper->saveToCache($key, $name, $activity);
        }
        return $activity;
    }

    public function findActivityOrderedByCreatedASC()
    {
        $key = ActivityCache::KEY_LIST_ASC;
        $name = ActivityCache::CACHE_ACTIVITY;
        $activity = $this->cacheHelper->getFromCache($key, $name, "ArrayCollection<DW\DWBundle\Entity\Activity>");
        if ($activity == null) {
            $activity = $this->activityRepository->findActivityOrderedByCreatedASC();
            $this->cacheHelper->saveToCache($key, $name, $activity);
        }
        return $activity;
    }

    public function findAmountForRecentWidget()
    {
       return $this->activityRepository->findAmountForRecentWidget();
    }

    public function findRawActivity()
    {
        return $this->activityRepository->findRawActivity();
    }

    public function findActivityByUserObjectType($user, $objectId, $type)
    {
        return $this->activityRepository->findActivityByUserObjectType($user, $objectId, $type);
    }

    public function findPreviousGroupNumber($groupNumber)
    {
        return $this->activityRepository->findPreviousGroupNumber($groupNumber);
    }

    public function findActivityByGroupNumber($groupNumber)
    {
        return $this->activityRepository->findActivityByGroupNumber($groupNumber);
    }

    public function updateActivityForMigration($id, $data)
    {
        $this->updateActivityForMigration($id, $data);
    }

    public function merge($entity)
    {
        $this->activityRepository->merge($entity);
    }

    public function save($entity, $sync = true)
    {
        $this->activityRepository->save($entity, $sync);
    }

    public function flush()
    {
        $this->activityRepository->flush();
    }
}