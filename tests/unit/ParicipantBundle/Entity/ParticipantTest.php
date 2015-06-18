<?php

use EL\ParticipantBundle\Entity\Participant;
use EL\ManagerBundle\Entity\Manager;
use EL\TeamBundle\Entity\Team;
use EL\CompetitionBundle\Entity\League;
use EL\CompetitionBundle\Entity\Season;
use EL\MatchBundle\Entity\Match;

class ParticipantTest extends \Codeception\TestCase\Test
{
    public function testManager()
    {
        $team = new Team();
        $team->setId(99);
        $team->setName('team');

        $manager = new Manager();
        $manager->setTeam($team);

        $participant = new Participant();
        $participant->setManager($manager);

        $this->assertNotNull($participant->getManager());
        $this->assertEquals($manager, $participant->getManager());
    }

    public function testCompetition()
    {
        $competition = new League();
        $competition->setName('League');

        $participant = new Participant();
        $participant->setCompetition($competition);

        $this->assertNotNull($participant->getCompetition());
        $this->assertEquals($competition, $participant->getCompetition());
    }

    public function testSeason()
    {
        $season = new Season();
        $season->setStartDate(new \DateTime('2015-01-01 12:00:00'));
        $season->setEndDate(new \DateTime('2015-01-2 14:00:00'));

        $participant = new Participant();
        $participant->setSeason($season);

        $this->assertNotNull($participant->getSeason());
        $this->assertEquals($season, $participant->getSeason());
    }

    public function testFrom()
    {
        $from = new \DateTime('2015-01-01 12:00:00');

        $participant = new Participant();
        $participant->setFrom($from);

        $this->assertNotNull($participant->getFrom());
        $this->assertEquals($from, $participant->getFrom());
    }

    public function testTo()
    {
        $to = new \DateTime('2015-01-01 12:00:00');

        $participant = new Participant();
        $participant->setTo($to);

        $this->assertNotNull($participant->getTo());
        $this->assertEquals($to, $participant->getTo());
    }

    public function testAddHomeMatches()
    {
        $participant = new Participant();
        $participant->addHomeMatch($this->createMatch(0, 0));
        $participant->addHomeMatch($this->createMatch(1, 0));
        $participant->addHomeMatch($this->createMatch(1, 1));
        $participant->addHomeMatch($this->createMatch(0, 1));
    }

    public function testGetHomeMatches()
    {
        $participant = new Participant();
        $participant->addHomeMatch($this->createMatch(0, 0));
        $participant->addHomeMatch($this->createMatch(1, 0));
        $participant->addHomeMatch($this->createMatch(1, 1));
        $participant->addHomeMatch($this->createMatch(0, 1));

        $homeMatches = $participant->getHomeMatches();
        $this->assertNotNull($homeMatches);
        $this->assertEquals(4, $homeMatches->count());
    }

    public function testAddAwayMatches()
    {
        $participant = new Participant();
        $participant->addAwayMatch($this->createMatch(0, 0));
        $participant->addAwayMatch($this->createMatch(1, 0));
        $participant->addAwayMatch($this->createMatch(1, 1));
        $participant->addAwayMatch($this->createMatch(0, 1));
    }

    public function testGetAwayMatches()
    {
        $participant = new Participant();
        $participant->addAwayMatch($this->createMatch(0, 0));
        $participant->addAwayMatch($this->createMatch(1, 0));
        $participant->addAwayMatch($this->createMatch(1, 1));
        $participant->addAwayMatch($this->createMatch(0, 1));

        $awayMatches = $participant->getAwayMatches();
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
}