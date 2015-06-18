<?php

use \FunctionalTester as Tester;
use EL\CompetitionBundle\Manager\CompetitionManager as CompetitionService;
use EL\CompetitionBundle\Manager\SeasonManager as SeasonService;

class CompetitionServiceCest
{
    /** @var CompetitionService */
    private $competitionService;

    /** @var SeasonService */
    private $seasonService;

    public function _before(Tester $I)
    {
        $serviceContainer = $I->getContainer();
        $this->competitionService = $serviceContainer->get('elite_fifa.competition_manager');
        $this->seasonService = $serviceContainer->get('elite_fifa.season_manager');
    }

    public function _after(Tester $I)
    {
        /* blank */
    }

    public function testGetCompetitionByName(Tester $I)
    {
        $competition = $this->competitionService->getCompetitionByName('League 1');
        $I->assertNotNull($competition);
        $I->assertEquals('League 1', $competition->getName());
    }

    public function testGetCompetitionBySlug(Tester $I)
    {
        $competition = $this->competitionService->getCompetitionBySlug('league-1');
        $I->assertNotNull($competition);
        $I->assertEquals('league-1', $competition->getSlug());
    }

    /**
    public function testGetStandingsByLeagueAndSeason(Tester $I)
    {
        $competition = $this->competitionService->getCompetitionBySlug('league-1');
        $I->assertNotNull($competition);
        $season = $this->seasonService->getSeasonByCompetitionAndNumber($competition, 1);
        $I->assertNotNull($season);

        $standings = $this->competitionService->getStandingsByCompetitionAndSeason($competition, $season);
        $I->assertNotNull($standings);
        $I->assertEquals(12, count($standings));

        $this->testStanding($I, $standings[0], 'Milan', 'user9', 41);
        $this->testStanding($I, $standings[1], 'Manchester United', 'user7', 38);
        $this->testStanding($I, $standings[2], 'Bayern Munich', 'user8', 33);
        $this->testStanding($I, $standings[3], 'Real Madrid', 'user2', 32);
        $this->testStanding($I, $standings[4], 'Arsenal', 'user6', 32);
        $this->testStanding($I, $standings[5], 'Chelsea', 'user4', 30);
        $this->testStanding($I, $standings[6], 'Tottenham Hotspur', 'user11', 30);
        $this->testStanding($I, $standings[7], 'Athletico Madrid', 'user10', 27);
        $this->testStanding($I, $standings[8], 'Napoli', 'user13', 25);
        $this->testStanding($I, $standings[9], 'Barcelona', 'user5', 24);
        $this->testStanding($I, $standings[10], 'Roma', 'user12', 21);
        $this->testStanding($I, $standings[11], 'Borussia Dortmund', 'user3', 21);
    }

    private function testStanding(Tester $I, $standing, $teamName, $username, $points)
    {
        $I->assertEquals($teamName, $standing->getTeam()->getName());
        $I->assertEquals($username, $standing->getUser()->getUsername());
        $I->assertEquals($points, $standing->getPoints());
    }

    public function testGetHomeStandingsByCompetitionAndSeason(Tester $I)
    {
        $competition = $this->competitionService->getCompetitionBySlug('league-1');
        $I->assertNotNull($competition);
        $season = $this->seasonService->getSeasonByCompetitionAndNumber($competition, 1);
        $I->assertNotNull($season);

        $standings = $this->competitionService->getStandingsByCompetitionAndSeason($competition, $season);
        $I->assertNotNull($standings);
        $I->assertEquals(12, count($standings));

        $this->testStanding($I, $standings[0], 'Milan', 'user9', 41);
        $this->testStanding($I, $standings[1], 'Manchester United', 'user7', 38);
        $this->testStanding($I, $standings[2], 'Bayern Munich', 'user8', 33);
        $this->testStanding($I, $standings[3], 'Real Madrid', 'user2', 32);
        $this->testStanding($I, $standings[4], 'Arsenal', 'user6', 32);
        $this->testStanding($I, $standings[5], 'Chelsea', 'user4', 30);
        $this->testStanding($I, $standings[6], 'Tottenham Hotspur', 'user11', 30);
        $this->testStanding($I, $standings[7], 'Athletico Madrid', 'user10', 27);
        $this->testStanding($I, $standings[8], 'Napoli', 'user13', 25);
        $this->testStanding($I, $standings[9], 'Barcelona', 'user5', 24);
        $this->testStanding($I, $standings[10], 'Roma', 'user12', 21);
        $this->testStanding($I, $standings[11], 'Borussia Dortmund', 'user3', 21);
    }**/
}