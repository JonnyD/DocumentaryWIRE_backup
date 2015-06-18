<?php

use \FunctionalTester as Tester;
use EL\ContestantBundle\Service\ContestantService;
use EL\TeamBundle\Manager\TeamManager as TeamService;
use EL\UserBundle\Manager\UserManager as UserService;
use EL\CompetitionBundle\Manager\CompetitionManager as CompetitionService;
use EL\CompetitionBundle\Manager\SeasonManager as SeasonService;

class ContestantCest
{
    /** @var ContestantService */
    private $contestantService;

    /** @var TeamService */
    private $teamService;

    /** @var UserService */
    private $userService;

    /** @var CompetitionService */
    private $competitionService;

    /** @var SeasonService */
    private $seasonService;

    public function _before(Tester $I)
    {
        $serviceContainer = $I->getContainer();
        $this->contestantService = $serviceContainer->get('elite_fifa.contestant_service');
        $this->teamService = $serviceContainer->get('elite_fifa.team_manager');
        $this->userService = $serviceContainer->get('elite_fifa.user_manager');
        $this->competitionService = $serviceContainer->get('elite_fifa.competition_manager');
        $this->seasonService = $serviceContainer->get('elite_fifa.season_manager');
    }

    public function _after(Tester $I)
    {
        /* blank */
    }

    public function testPersist(Tester $I)
    {
        /**
        $user = $this->userService->getUserByUsername('user1');
        $team = $this->teamService->getTeamByName('Liverpool');
        $competition = $this->competitionService->getCompetitionByName('League 1');
        $season = $this->seasonService->getSeasonByCompetitionAndNumber($competition, 1);

        $contestant = $this->contestantService->getContestantByUserTeamCompetitionSeason($user, $team, $competition, $season);
        $I->assertNotNull($contestant);
         * **/
    }
}