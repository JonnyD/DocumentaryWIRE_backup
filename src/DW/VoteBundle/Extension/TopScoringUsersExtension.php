<?php

namespace DW\VoteBundle\Extension;

use DW\VoteBundle\Service\VoteService;
use Symfony\Component\HttpFoundation\Session\Session;

class TopScoringUsersExtension extends \Twig_Extension
{
    /**
     * @var VoteService $voteService
     */
    private $voteService;

    /**
     * @param VoteService $voteService
     */
    public function __construct(VoteService $voteService)
    {
        $this->voteService = $voteService;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'topScoringUsers' => new \Twig_Function_Method($this, 'topScoringUsers')
        );
    }

    /**
     * @return mixed
     */
    public function topScoringUsers()
    {
        $users = $this->voteService->getTopScoringUsers();
        return $users;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'topScoringUsersWidgetExtension';
    }
}