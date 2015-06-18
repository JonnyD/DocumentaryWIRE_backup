<?php

use EL\MatchBundle\Entity\Match;
use EL\ParticipantBundle\Entity\Participant;

class MatchTest extends \Codeception\TestCase\Test
{
    protected function _before()
    {

    }

    protected function _after()
    {

    }

    private function createMatch($homeScore, $awayScore)
    {
        $match = new Match(Match::createBuilder());
        $match->setHomeScore($homeScore);
        $match->setAwayScore($awayScore);
        return $match;
    }

    private function createParticipant($from, $to)
    {
        $participant = new Participant();
        $participant->setFrom(new \DateTime($from));
        $participant->setTo(new \DateTime($to));
        return $participant;
    }

    public function testHomeParticipant()
    {
        $participant = $this->createParticipant(
            '2015-01-01 12:00:00', '2015-01-02 12:00:00');

        $match = $this->createMatch(1, 0);
        $match->setHomeParticipant($participant);

        $this->assertNotNull($match->getHomeParticipant());
        $this->assertEquals($participant, $match->getHomeParticipant());
    }

    public function testAwayParticipant()
    {
        $participant = $this->createParticipant(
            '2015-01-01 12:00:00', '2015-01-02 12:00:00');

        $match = $this->createMatch(1, 0);
        $match->setAwayParticipant($participant);

        $this->assertNotNull($match->getAwayParticipant());
        $this->assertEquals($participant, $match->getAwayParticipant());
    }

    public function testRound()
    {
        $match = new Match(Match::createBuilder());
        $match->setRound(10);

        $this->assertEquals(10, $match->getRound());
    }

    public function testScore()
    {
        $match = new Match(Match::createBuilder());
        $match->setHomeScore(1);
        $match->setAwayScore(2);

        $this->assertEquals(1, $match->getHomeScore());
        $this->assertEquals(2, $match->getAwayScore());
    }
}