<?php

namespace DW\CommentBundle\Controller;

use DW\CommentBundle\Form\Type\CommentType;
use DW\CommentBundle\Service\CommentServices;
use DW\DocumentaryBundle\Service\DocumentaryServices;
use DW\VoteBundle\Service\VoteServices;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use DW\CommentBundle\Entity\Comment;
use DW\DocumentaryBundle\Service\DocumentaryService;
use DW\CommentBundle\Service\CommentService;
use DW\VoteBundle\Service\VoteService;

class CommentController extends Controller
{   	
	public function voteAction()
	{
        $commentService = $this->getCommentService();
        $voteService = $this->getVoteService();
        $userHelper = $this->get('documentary_wire.user_helper');

        $request = $this->container->get('request');
		$action = $request->get('action');
		$commentId = $request->get('commentId');
		
		$user = $userHelper->getLoggedInUser();
        if ($user != null) {
            $comment = $commentService->getCommentById($commentId);
            $vote = $voteService->getVoteByUserAndComment($user, $comment);
            $points = 0;
            if ($vote == null) {
                $points = $voteService->createVote($user, $comment, $action);
            } else if ($vote != null) {
                $points = $voteService->updateVote($vote, $action);
            }

            $headers = array(
                'Content-Type' => 'application/json'
            );
            $response = array(
                "code" => 100,
                "success" => true,
                "error" => "",
                "points" => $points
            );
            return new Response(json_encode($response), 200, $headers);
        }
	}

    public function newAction($documentarySlug)
    {
        $documentaryService = $this->getDocumentaryService();
        $documentary = $documentaryService->getDocumentaryBySlug($documentarySlug);

        $comment = new Comment();
        $comment->setDocumentary($documentary);
        $comment->setCreated(new \DateTime());

        $form = $this->createForm(new CommentType(), $comment);

        return $this->render('DocumentaryWIREBundle:Comment:form.html.twig', array(
            'comment' => $comment,
            'form'   => $form->createView()
        ));
    }

    /**
     * @return CommentService
     */
    private function getCommentService()
    {
        return $this->container->get(CommentServices::COMMENT);
    }

    /**
     * @return DocumentaryService
     */
    private function getDocumentaryService()
    {
        return $this->container->get(DocumentaryServices::DOCUMENTARY);
    }

    /**
     * @return VoteService
     */
    private function getVoteService()
    {
        return $this->container->get(VoteServices::VOTE);
    }
}