<?php

namespace DW\LikeBundle\Repository\Cached;

use Doctrine\ORM\EntityRepository;
use DW\BaseBundle\Cache\LikeCache;
use DW\DocumentaryBundle\Entity\Documentary;
use DW\LikeBundle\Entity\Like;
use DW\UserBundle\Entity\User;
use DW\BaseBundle\Helper\CacheHelper;
use DW\LikeBundle\Repository\LikeRepository as LikeRepositoryInterface;
use DW\LikeBundle\Repository\Doctrine\ORM\LikeRepository as LikeRepositoryDoctrine;

class LikeRepository extends EntityRepository implements LikeRepositoryInterface
{
    private $likeRepository;
    private $cacheHelper;

    public function __construct(LikeRepositoryDoctrine $likeRepository, CacheHelper $cacheHelper)
    {
        $this->likeRepository = $likeRepository;
        $this->cacheHelper = $cacheHelper;
    }

    public function remove(Like $like)
    {
        $this->likeRepository->remove($like);
        $this->cacheHelper->deleteFromCache('user_id_'.$like->getUserId(), 'likes');
        $this->cacheHelper->deleteFromCache('documentary_slug_'.$like->getDocumentarySlug(), 'documentary');
    }

    public function findAllLikes()
    {
        return $this->likeRepository->findAll();
    }

    public function deleteLikeByUserAndDocumentary(User $user, Documentary $documentary)
    {
        $this->likeRepository->deleteLikeByUserAndDocumentary($user, $documentary);
        $this->cacheHelper->deleteFromCache('user_id_'.$user->getId(), 'likes');
        $this->cacheHelper->deleteFromCache('documentary_slug_'.$documentary->getSlug(), 'documentary');
    }

    public function findLikesByUser(User $user)
    {
        $key = LikeCache::KEY_USER_ID."_".$user->getId();
        $name = LikeCache::CACHE_LIKES;

        $likedDocumentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($likedDocumentaries == null && !is_array($likedDocumentaries)) {
            $likes = $this->likeRepository->findLikesByUser($user);

            $likedDocumentaries = array();
            foreach ($likes as $like) {
                $documentary = $like->getDocumentary();
                $slug = $documentary->getSlug();
                $likedDocumentaries[$slug] = $like;
            }

            $this->cacheHelper->saveToCache($key, $name, $likedDocumentaries);
        }

        return $likedDocumentaries;
    }

    public function findLikeByUserAndDocumentary(User $user, Documentary $documentary)
    {
        return $this->likeRepository->findLikeByUserAndDocumentary($user, $documentary);
    }

    public function findLikesByDocumentary(Documentary $documentary)
    {
        return $this->likeRepository->findLikesByDocumentary($documentary);
    }

    public function merge($entity)
    {
        $this->likeRepository->merge($entity);
    }

    public function save($entity, $sync = true)
    {
        $this->likeRepository->save($entity, $sync);
    }

    public function flush()
    {
        $this->likeRepository->flush();
    }
}