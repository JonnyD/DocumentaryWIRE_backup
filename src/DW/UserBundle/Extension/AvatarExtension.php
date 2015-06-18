<?php

namespace DW\UserBundle\Extension;

use DW\UserBundle\Entity\User;
use DW\UserBundle\Service\UserService;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class AvatarExtension extends \Twig_Extension
{
    /**
     * @var UserService $userService
     */
    private $userService;
    private $imagineManager;

    public function __construct(UserService $userService, CacheManager $cacheManager)
    {
        $this->userService = $userService;
        $this->imagineManager = $cacheManager;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'getAvatar' => new \Twig_Function_Method($this, 'getAvatar')
        );
    }

    /**
     * @param $user
     * @param $filter
     * @return string
     */
    public function getAvatar($user, $filter)
    {
        if ($user instanceof User) {
            $avatarFile = $user->getAvatar();
            $email = $user->getEmail();
        } else {
            $avatarFile = $user["avatar"];
            $email = $user["email"];
        }

        if ($avatarFile == null) {
            $avatar = $this->userService->getGravatar($email);
        } else {
            $avatar = 'uploads/images/avatar/' . $avatarFile;
            $avatar = $this->imagineManager->getBrowserPath($avatar, $filter);
        }

        return $avatar;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'getAvatarExtension';
    }
}