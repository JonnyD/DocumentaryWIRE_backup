<?php

namespace DW\UserBundle\Event\Listener;

use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use DW\UserBundle\Service\UserService as UserManager;
use DW\BaseBundle\Helper\UserHelper;
use DW\BaseBundle\Helper\CacheHelper;

class SecurityListener
{
    private $userManager;
    private $cacheHelper;
    private $userHelper;
    private $router;

    public function __construct(UserManager $userManager, CacheHelper $cacheHelper,
                                UserHelper $userHelper, Router $router)
    {
        $this->userManager = $userManager;
        $this->userHelper = $userHelper;
        $this->cacheHelper = $cacheHelper;
        $this->router = $router;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $this->userHelper->getLoggedInUser();
        if ($user->getActivated() != null) {
            $user->setLastActive(new \DateTime());
            $this->userManager->persist($user);
            $this->cacheHelper->deleteFromCache("active", "users");
        }
    }

}