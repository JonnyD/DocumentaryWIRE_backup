<?php

use \FunctionalTester as Tester;
use Mockery as m;
use EL\UserBundle\Entity\User;
use EL\TeamBundle\Entity\Team;
use EL\UserBundle\Repository\UserRepository;
use EL\TeamBundle\Repository\TeamRepository;
use EL\ManagerBundle\Repository\ManagerRepository;

class ManagerRepositoryCest
{
    /** @var ManagerRepository $managerRepository */
    private $managerRepository;

    /** @var UserRepository $userRepository */
    private $userRepository;

    /** @var TeamRepository $teamRepository */
    private $teamRepository;

    public function _before(Tester $I)
    {
        $serviceContainer = $I->getContainer();
        $this->managerRepository = $serviceContainer->get('elite_fifa.manager_repository');
        $this->userRepository = $serviceContainer->get('elite_fifa.user_repository');
        $this->teamRepository = $serviceContainer->get('elite_fifa.team_repository');
    }

    public function _after(Tester $I)
    {
       m::close();
    }

    public function testFindManagerByUserAndTeam(Tester $I)
    {
        $user = $this->userRepository->findOneByUsername('user1');
        $team = $this->teamRepository->findOneByName('liverpool');

        $manager = $this->managerRepository->findOneByUserAndTeam($user, $team);
        $I->assertNotNull($manager);
        $I->assertEquals($user, $manager->getUser());
        $I->assertEquals($team, $manager->getTeam());
    }
}