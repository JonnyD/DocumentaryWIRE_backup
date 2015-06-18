<?php

use EL\MatchBundle\Manager\MatchManager as MatchService;
use EL\MatchBundle\Entity\Match;
use Doctrine\Common\Collections\ArrayCollection;
use Mockery as m;

class MatchServiceTest extends \Codeception\TestCase\Test
{
    const MATCH_REPOSITORY = 'EL\MatchBundle\Repository\MatchRepository';
    const USER_ENTITY = 'EL\UserBundle\Entity\User';
    const TEAM_ENTITY = 'EL\TeamBundle\Entity\Team';

    private $entityManager;
    private $matchRepository;
    private $formFactory;

    /** @var ArrayCollection|Match[] */
    private $matches;

    protected function _before()
    {
        $this->entityManager = m::mock();
        $this->matchRepository = m::mock(MatchServiceTest::MATCH_REPOSITORY);
        $this->formFactory = m::mock();

        $matches = new ArrayCollection();
        $matches->add($this->createMatch(0, 0));
        $matches->add($this->createMatch(1, 0));
        $matches->add($this->createMatch(1, 0));
        $matches->add($this->createMatch(1, 1));
        $this->matches = $matches;
    }

    protected function _after()
    {
        m::close();
    }

    private function getMatchService()
    {
        return new MatchService(
            $this->entityManager,
            $this->matchRepository,
            $this->formFactory);
    }

    private function createMatch($homeScore, $awayScore)
    {
        $match = new Match(Match::createBuilder());
        $match->setHomeScore($homeScore);
        $match->setAwayScore($awayScore);
        return $match;
    }

    public function testGetAllMatches()
    {
        $this->matchRepository = m::mock(MatchServiceTest::MATCH_REPOSITORY)
            ->shouldReceive('findAll')
            ->andReturn($this->matches)
            ->getMock();

        $matchService = $this->getMatchService();
        $allMatches = $matchService->getAllMatches();

        $this->assertNotNull($allMatches);
        $this->assertEquals(4, $allMatches->count());
        foreach ($this->matches as $match) {
            $this->assertTrue(in_array($match, $allMatches));
        }
    }

    public function testGetMatchesByUser()
    {
        $user = m::mock(MatchServiceTest::USER_ENTITY)
        ->shouldReceive('getUsername')
        ->andReturn('user')
        ->getMock();

        $this->matchRepository = m::mock(MatchServiceTest::MATCH_REPOSITORY)
            ->shouldReceive('findMatchesByUser')
            ->with($user)
            ->andReturn($this->matches)
            ->getMock();

        $matchService = $this->getMatchService();
        $matchesByUser = $matchService->getMatchesByUser($user);

        $this->assertNotNull($matchesByUser);
        $this->assertEquals(4, $matchesByUser->count());
        foreach ($this->matches as $match) {
            $this->assertTrue(in_array($match, $matchesByUser));
        }
    }

    public function testGetMatchesByTeam()
    {
        $team = m::mock(MatchServiceTest::TEAM_ENTITY)
            ->shouldReceive('getName')
            ->andReturn('team')
            ->getMock();

        $this->matchRepository = m::mock(MatchServiceTest::TEAM_ENTITY)
            ->shouldReceive('findMatchesByTeam')
            ->with($team)
            ->andReturn($this->matches)
            ->getMock();

        $matchService = $this->getMatchService();
        $matchesByTeam = $matchService->getAllMatchesByTeam($team);

        $this->assertNotNull($matchesByTeam);
        $this->assertEquals(4, $matchesByTeam->count());
        foreach ($this->matches as $match) {
            $this->assertTrue(in_array($match, $matchesByTeam));
        }
    }
}