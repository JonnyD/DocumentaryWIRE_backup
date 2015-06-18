<?php

use EL\ParticipantBundle\Repository\ParticipantRepository;
use EL\ParticipantBundle\Service\ParticipantService;
use Mockery as m;

class ParticipantServiceTest extends \Codeception\TestCase\Test
{
    const PARTICIPANT_REPOSITORY = 'EL\ParticipantBundle\Repository\ParticipantRepository';
    const USER_ENTITY = 'EL\UserBundle\Entity\User';
    const TEAM_ENTITY = 'EL\TeamBundle\Entity\Team';
    const PARTICIPANT_ENTITY = 'EL\ParticipantBundle\Entity\Participant';

    /** @var ParticipantRepository $participantRepository */
    private $participantRepository;

    protected function _before()
    {
        $this->participantRepository = m::mock('ParticipantRepository');
    }

    protected function _after()
    {
        m::close();
    }

    /**
     * @return ParticipantService
     */
    private function getParticipantService()
    {
        return new ParticipantService($this->participantRepository);
    }

    public function testGetParticipantByUserAndTeam()
    {
        $user = m::mock(ParticipantServiceTest::USER_ENTITY);
        $team = m::mock(ParticipantServiceTest::TEAM_ENTITY);

        $participant = m::mock(ParticipantServiceTest::PARTICIPANT_ENTITY)
            ->shouldReceive('getId')
            ->andReturn(99)
            ->getMock();

        $this->participantRepository = m::mock(ParticipantServiceTest::PARTICIPANT_REPOSITORY)
            ->shouldReceive('findParticipant')
            ->with($user, $team)
            ->andReturn($participant)
            ->getMock();

        $participantService = $this->getParticipantService();
        $returnedParticipant = $participantService->getParticipantByUserAndTeam($user, $team);

        $this->assertNotNull($participant);
        $this->assertEquals(99, $participant->getId());
        $this->assertEquals($returnedParticipant, $participant);
    }
}