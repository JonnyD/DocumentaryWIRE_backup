<?php

namespace DW\LikeBundle\Extension;

use DW\UserBundle\Entity\User;
use DW\LikeBundle\Service\LikeService;

class HasLikedExtension extends \Twig_Extension
{
    /**
     * @var LikeService $likeService
     */
    private $likeService;

    /**
     * @param LikeService $likeService
     */
    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'hasLiked' => new \Twig_Function_Method($this, 'hasLiked')
        );
    }

    /**
     * @param null $user
     * @param $documentarySlug
     * @return bool
     */
    public function hasliked($user = null, $documentarySlug)
    {
        $hasLiked = false;
        if ($user != null && $user instanceof User) {
            $hasLiked = $this->likeService->hasLiked($user, $documentarySlug);
        }
        return $hasLiked;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'haslikedExtension';
    }
}