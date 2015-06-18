<?php

use EL\UserBundle\Entity\User;
use EL\UserBundle\Manager\UserManager as UserService;
use Doctrine\ORM\EntityManager;
use EL\UserBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Mockery as m;

class UserServiceTest extends \Codeception\TestCase\Test
{
    const ENTITY_MANAGER = 'Doctrine\ORM\EntityManager';
    const USER_REPOSITORY = 'EL\UserBundle\Repository\UserRepository';
    const SECURITY_CONTEXT = 'Symfony\Component\Security\Core\SecurityContext';
    const ENCODER_FACTORY = 'Symfony\Component\Security\Core\Encoder\EncoderFactory';

    /** @var EntityManager $entityManager */
    private $entityManager;

    /** @var UserRepository $userRepository */
    private $userRepository;

    /** @var SecurityContext $securityContext */
    private $securityContext;

    /** @var EncoderFactory $encoderFactory */
    private $encoderFactory;

    protected function _before()
    {
        $this->entityManager = m::mock(UserServiceTest::ENTITY_MANAGER);
        $this->userRepository = m::mock(UserServiceTest::USER_REPOSITORY);
        $this->securityContext = m::mock(UserServiceTest::SECURITY_CONTEXT);
        $this->encoderFactory = m::mock(UserServiceTest::ENCODER_FACTORY);
    }

    protected function _after()
    {
        m::close();
    }

    protected function getUserService()
    {
        return new UserService(
            $this->entityManager,
            $this->userRepository,
            $this->securityContext,
            $this->encoderFactory);
    }

    /**
    public function testCreateUser()
    {
        $userService = $this->getUserService();

        $user = $userService->createUser('username', 'email@email', 'pass', 'user');
        $this->assertNotNull($user);
        $this->assertEquals('username', $user->getUsername());
        $this->assertEquals('email@email.com', $user->getEmail());
        $this->assertEquals('pass', $user->getPassword());
        $this->assertEquals('user', $user->getRoles());
    }**/

    public function getAllUsers()
    {
        $user1 = new User();
        $user1->setId(1);
        $user1->setUsername('user1');

        $user2 = new User();
        $user2->setId(2);
        $user2->setUsername('user2');

        $user3 = new User();
        $user3->setId(3);
        $user3->setUsername('user3');

        $usersCollection = new ArrayCollection();
        $usersCollection->add($user1);
        $usersCollection->add($user2);
        $usersCollection->add($user3);

        $this->userRepository = m::mock(UserServiceTest::USER_REPOSITORY)
            ->shouldReceive('findAllUsers')
            ->andReturn($usersCollection)
            ->getMock();

        $userService = $this->getUserService();
        $allUsers = $userService->getAllUsers();

        $this->assertNotNull($allUsers);
        $this->assertEquals(3, $allUsers->count());

        foreach ($allUsers as $user) {
            $this->assertTrue(in_array($user, $allUsers));
        }
    }

    public function testGetUserById()
    {
        $user = new User();
        $user->setId(99);
        $user->setUsername('user');

        $this->userRepository = m::mock(UserServiceTest::USER_REPOSITORY)
            ->shouldReceive('find')
            ->with(99)
            ->andReturn($user)
            ->getMock();

        $userService = $this->getUserService();
        $userById = $userService->getUserById(99);

        $this->assertNotNull($userById);
        $this->assertEquals(99, $userById->getId());
    }

    public function getUserByUsername()
    {
        $user = new User();
        $user->setId(99);
        $user->setUsername('user');

        $this->userRepository = m::mock(UserServiceTest::USER_REPOSITORY)
            ->shouldReceive('findUserByUsername')
            ->with('user')
            ->andReturn($user)
            ->getMock();

        $userService = $this->getUserService();
        $userByUsername = $userService->getUserByUsername('user');

        $this->assertNotNull($userByUsername);
        $this->assertEquals('user', $userByUsername->getUsername());
    }
}