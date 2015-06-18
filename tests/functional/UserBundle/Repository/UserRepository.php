<?php

use \FunctionalTester as Tester;
use EL\CompetitionBundle\Service\CompetitionServices;

class UserRepository
{

    public function _before(Tester $I)
    {
        $serviceContainer = $I->getContainer();
        $this->competitionService = $serviceContainer->get(CompetitionServices::COMPETITION_SERVICE);
        $this->seasonService = $serviceContainer->get(CompetitionServices::SEASON_SERVICE);
    }

    public function _after(Tester $I)
    {
        /* blank */
    }
}