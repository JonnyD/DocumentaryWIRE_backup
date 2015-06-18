<?php

use EL\ManagerBundle\Repository\ManagerRepository;
use EL\ManagerBundle\Service\ManagerService;
use Mockery as m;

class ManagerServiceTest extends \Codeception\TestCase\Test
{
    const MANAGER_REPOSITORY = 'EL\ManagerBundle\Repository\ManagerRepository';
    const USER_ENTITY = 'EL\UserBundle\Entity\User';
    const TEAM_ENTITY = 'EL\TeamBundle\Entity\Team';
    const MANAGER_ENTITY = 'EL\ManagerBundle\Entity\Manager';

    /** @var ManagerRepository $managerRepository */
    private $managerRepository;

    protected function _before()
    {
        $this->managerRepository = m::mock('EL\ManagerBundle\Repository\ManagerRepository');
    }

    protected function getManagerService()
    {
        return new ManagerService($this->managerRepository);
    }

    protected function _after()
    {
        m::close();
    }

    public function testGetManagerByUserAndTeam()
    {
        $user = m::mock(ManagerServiceTest::USER_ENTITY)
            ->shouldReceive([
                'getId' => 99,
                'getUsername' => 'user'
            ])
            ->getMock();

        $team = m::mock(ManagerServiceTest::TEAM_ENTITY)
            ->shouldReceive([
                'getId' => 99,
                'getName' => 'team'
            ])
            ->getMock();

        $manager = m::mock(ManagerServiceTest::MANAGER_ENTITY)
            ->shouldReceive([
                'getUser' => $user,
                'getTeam' => $team
            ])
            ->getMock();

        $this->managerRepository = m::mock(ManagerServiceTest::MANAGER_REPOSITORY)
            ->shouldReceive('findOneByUserAndTeam')
            ->with($user, $team)
            ->andReturn($manager)
            ->getMock();

        $managerService = $this->getManagerService();
        $manager = $managerService->getManagerByUserAndTeam($user, $team);

        $this->assertNotNull($manager);
        $this->assertEquals($user, $manager->getUser());
        $this->assertEquals('user', $manager->getUser()->getUsername());
        $this->assertEquals($team, $manager->getTeam());
        $this->assertEquals('team', $manager->getTeam()->getName());
    }
}