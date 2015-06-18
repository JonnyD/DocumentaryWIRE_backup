<?php

namespace DW\UserBundle\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use DW\UserBundle\Entity\Profile;
use DW\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\UserBundle\Doctrine\UserManager as FosUserManager;
use DW\UserBundle\Service\UserService;

class UserFixtures extends AbstractFixture implements ContainerAwareInterface
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

        for ($i = 0; $i < 4; $i++) {
            $this->createUser("ROLE_USER", $i);
        }
        $manager->flush();
	}

    private function createUser($role, $number)
    {
        $username = "user".$number;
        $usernameCanonical = $username;
        $email = $username."@email.com";
        $emailCanonical = $email;
        $password = "pass".$number;

        $userManager = $this->getFosUserManager();

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled(1);
        $user->addRole($role);
        $user->setDisplayName($username);

        $this->addReference($username, $user);

        $this->manager->persist($user);
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