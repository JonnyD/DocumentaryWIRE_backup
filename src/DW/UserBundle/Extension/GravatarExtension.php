<?php

namespace DW\UserBundle\Extension;

use DW\UserBundle\Service\UserService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GravatarExtension extends \Twig_Extension
{
    /**
     * @var UserService $userService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'getGravatar' => new \Twig_Function_Method($this, 'getGravatar')
        );
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getGravatar($email)
    {
        $gravatar = $this->userService->getGravatar($email);
        return $gravatar;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gravatarExtension';
    }
}