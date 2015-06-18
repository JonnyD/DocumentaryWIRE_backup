<?php

use EL\UserBundle\Entity\User;
use EL\TeamBundle\Entity\Team;
use EL\MatchBundle\Entity\Match;
use EL\ManagerBundle\Entity\Manager;
use EL\ParticipantBundle\Entity\Participant;
use Mockery as m;

class ManagerTest extends \Codeception\TestCase\Test
{
    protected function _before()
    {
        /* blank */
    }

    protected function _after()
    {
        m::close();
    }

    public function testUser()
    {
        $user = new User();
        $user->setId(99);
        $user->setUsername('user');

        $manager = new Manager();
        $manager->setUser($user);

        $this->assertNotNull($manager->getUser());
        $this->assertEquals($user, $manager->getUser());
    }

    public function testTeam()
    {
        $team = new Team();
        $team->setId(99);
        $team->setName('Team');

        $manager = new Manager();
        $manager->setTeam($team);

        $this->assertNotNull($manager->getTeam());
        $this->assertEquals($team, $manager->getTeam());
    }

    public function testCheckHomeMatchesEmptyByDefault()
    {
        $manager = new Manager();
        $homeMatches = $manager->getHomeMatches();

        $this->assertNotNull($homeMatches);
        $this->assertEquals(0, $homeMatches->count());
    }

    public function testAddHomeMatch()
    {
        $manager = new Manager();
        $manager->addHomeMatch($this->createMatch(0, 0));
    }

    public function testGetHomeMatches()
    {
        $manager = new Manager();
        $manager->addHomeMatch($this->createMatch(0, 0));
        $manager->addHomeMatch($this->createMatch(1, 0));
        $manager->addHomeMatch($this->createMatch(1, 1));
        $manager->addHomeMatch($this->createMatch(0, 1));

        $homeMatches = $manager->getHomeMatches();
        $this->assertNotNull($homeMatches);
        $this->assertEquals(4, $homeMatches->count());
    }

    public function testAwayMatchesEmptyByDefault()
    {
        $manager = new Manager();
        $awayMatches = $manager->getAwayMatches();

        $this->assertNotNull($awayMatches);
        $this->assertEquals(0, $awayMatches->count());
    }

    public function testAddAwayMatch()
    {
        $manager = new Manager();
        $manager->addAwayMatch($this->createMatch(0, 0));
    }

    public function testGetAwayMatches()
    {
        $manager = new Manager();
        $manager->addAwayMatch($this->createMatch(0, 0));
        $manager->addAwayMatch($this->createMatch(1, 0));
        $manager->addAwayMatch($this->createMatch(1, 1));
        $manager->addAwayMatch($this->createMatch(0, 1));

        $awayMatches = $manager->getAwayMatches();
        $this->assertNotNull($awayMatches);
        $this->assertEquals(4, $awayMatches->count());
    }

    private function createMatch($homeScore, $awayScore)
    {
        $match = new Match(Match::createBuilder());
        $match->setHomeScore($homeScore);
        $match->setAwayScore($awayScore);
        return $match;
    }

    public function testParticipatesEmptyByDefault()
    {
        $manager = new Manager();
        $participants = $manager->getParticipation();

        $this->assertNotNull($participants);
        $this->assertEquals(0, $participants->count());
    }

    public function testAddParticipants()
    {
        $manager = new Manager();

        $participation1 = $this->createParticipation(
            '2015-01-01 12:00:00', '2015-01-02 12:00:00'
        );
        $participation2 = $this->createParticipation(
            '2015-01-03 12:00:00', '2015-01-04 12:00:00'
        );
        $participation3 = $this->createParticipation(
            '2015-01-05 12:00:00', '2015-01-06 12:00:00'
        );
        $participation4 = $this->createParticipation(
            '2015-01-07 12:00:00', '2015-01-08 12:00:00'
        );
        $participation5 = $this->createParticipation(
            '2015-01-09 12:00:00', '2015-01-10 12:00:00'
        );

        $manager->addParticipation($participation1);
        $manager->addParticipation($participation2);
        $manager->addParticipation($participation3);
        $manager->addParticipation($participation4);
        $manager->addParticipation($participation5);
    }

    public function testGetParticipants()
    {
        $manager = new Manager();

        $participation1 = $this->createParticipation(
            '2015-01-01 12:00:00', '2015-01-02 12:00:00'
        );
        $participation2 = $this->createParticipation(
            '2015-01-03 12:00:00', '2015-01-04 12:00:00'
        );
        $participation3 = $this->createParticipation(
            '2015-01-05 12:00:00', '2015-01-06 12:00:00'
        );
        $participation4 = $this->createParticipation(
            '2015-01-07 12:00:00', '2015-01-08 12:00:00'
        );
        $participation5 = $this->createParticipation(
            '2015-01-09 12:00:00', '2015-01-10 12:00:00'
        );

        $manager->addParticipation($participation1);
        $manager->addParticipation($participation2);
        $manager->addParticipation($participation3);
        $manager->addParticipation($participation4);
        $manager->addParticipation($participation5);

        $participants = $manager->getParticipation();
        $this->assertNotNull($participants);
        $this->assertEquals(5, $participants->count());
    }

    private function createParticipation($from, $to)
    {
        $participant = new Participant();
        $participant->setFrom(new \DateTime($from));
        $participant->setTo(new \DateTime($to));
        return $participant;
    }
}