<?php

namespace DW\UserBundle\Extension;

use DW\UserBundle\Service\UserService;
use DW\BaseBundle\Helper\UserHelper;
use Symfony\Component\HttpFoundation\Session\Session;

class UserWidgetExtension extends \Twig_Extension
{
    /**
     * @var UserService $userService
     */
    private $userService;
    private $session;

    public function __construct(UserService $userService, Session $session) {
        $this->userService = $userService;
        $this->session = $session;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'userWidget' => new \Twig_Function_Method($this, 'userWidget')
        );
    }

    /**
     * @param $type
     * @return mixed
     */
    public function userWidget($type)
    {
        if ($type == "newest") {
            $users = $this->userService->getLatestUsers();
        } else {
            $users = $this->userService->getActiveUsers();
        }
        return $users;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'userWidgetExtension';
    }
}