<?php

use \FunctionalTester as Tester;
use Mockery as m;
use EL\MatchBundle\Manager\MatchManager as MatchService;
use EL\TeamBundle\Manager\TeamManager as TeamService;

class MatchServiceCest
{
    /** @var MatchService $matchService */
    private $matchService;

    /** @var TeamService $teamService */
    private $teamService;

    public function _before(Tester $I)
    {
        $serviceContainer = $I->getContainer();
        $this->matchService = $serviceContainer->get('elite_fifa.match_manager');
        $this->teamService = $serviceContainer->get('elite_fifa.team_manager');
    }

    public function _after(Tester $I)
    {
        m::close();
    }

    public function testFindMatchesByTeam(Tester $I)
    {
        $team = $this->teamService->getTeamByName('liverpool');

        $matches = $this->matchService->getAllMatchesByTeam($team);

        $I->assertNotNull($matches);
    }
}