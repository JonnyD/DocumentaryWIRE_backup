<?php

namespace DW\VoteBundle\Extension;

use DW\CommentBundle\Entity\Comment;
use DW\UserBundle\Entity\User;
use DW\BaseBundle\Helper\UserHelper;
use DW\VoteBundle\Service\VoteService;

class FetchVoteExtension extends \Twig_Extension
{
    /**
     * @var VoteService $voteService
     */
    private $voteService;

    /**
     * @var UserHelper $userHelper
     */
    private $userHelper;

    /**
     * @param VoteService $voteService
     * @param UserHelper $userHelper
     */
    public function __construct(VoteService $voteService, UserHelper $userHelper)
    {
        $this->voteService = $voteService;
        $this->userHelper = $userHelper;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'fetchVote' => new \Twig_Function_Method($this, 'fetchVote')
        );
    }

    /**
     * @param User $user
     * @param Comment $comment
     * @return null
     */
    public function fetchVote(User $user = null, Comment $comment)
    {
        $vote = null;
        if ($user != null) {
            $vote = $this->voteService->getVoteByUserAndComment($user, $comment);
        }
        return $vote;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fetchVoteExtension';
    }
}