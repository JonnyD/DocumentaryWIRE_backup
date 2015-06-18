<?php

namespace DW\UserBundle\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use DW\UserBundle\Entity\Avatar;
use DW\UserBundle\Entity\Profile;
use DW\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\UserBundle\Doctrine\UserManager as FosUserManager;
use DW\UserBundle\Service\UserService;

class AvatarFixtures extends AbstractFixture implements ContainerAwareInterface
{
    private $container;
    private $manager;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        /** @var User[] $users */
        $users = [];
        for ($i = 0; $i < 4; $i++) {
            $username = "user".$i;
            $users[] = $this->getReference($username);
        }
        $manager->flush();

        foreach ($users as $user) {
            $avatar = new Avatar();
            $avatar->setAvatar($user->getUsername());
            $avatar->setUser($user);
            $avatar->setAsMainAvatar();

            $this->manager->persist($avatar);
        }

        $this->manager->flush();
    }

    /**
     * @return FosUserManager
     */
    private function getFosUserManager()
    {
        return $this->container->get('fos_user.user_manager');
    }

    /**
     * @return UserService
     */
    private function getUserService()
    {
        return $this->container->get('documentary_wire.user_manager');
    }
}