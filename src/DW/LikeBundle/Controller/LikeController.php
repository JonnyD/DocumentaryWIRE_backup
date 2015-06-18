<?php

namespace DW\LikeBundle\Controller;

use DW\ActivityBundle\Service\ActivityServices;
use DW\DocumentaryBundle\Service\DocumentaryServices;
use DW\ActivityBundle\Entity\ActivityComponent;
use DW\ActivityBundle\Entity\ActivityType;
use DW\LikeBundle\Service\LikeServices;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use DW\DocumentaryBundle\Service\DocumentaryService;
use DW\LikeBundle\Service\LikeService;
use DW\ActivityBundle\Service\ActivityService;

class LikeController extends Controller
{

    public function likeAction()
    {
        $request = $this->container->get('request');
        $actionType = $request->get('actionType');
        $documentaryId = $request->get('documentaryId');

        $securityContext = $this->container->get('security.context');
        if(!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')
            && !$securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        }
        $token = $securityContext->getToken();
        $user = $token->getUser();

        if ($user != null) {
            $documentaryManager = $this->getDocumentaryService();
            $documentary = $documentaryManager->getDocumentaryById($documentaryId);

            if ($documentary) {
                $likeManager = $this->getLikeService();
                $hasLiked = $likeManager->hasLiked($user, $documentary->getSlug());

                $activityManager = $this->getActivityService();
                $activity = $activityManager->getActivity($user, $documentary->getId(),
                    ActivityType::LIKE, ActivityComponent::DOCUMENTARY);

                if ($actionType === 'like')
                {
                    if (!$hasLiked) {
                        $like = $likeManager->likeDocumentary($user, $documentary);
                    }
                }
                else if ($actionType === 'unlike')
                {
                    if ($hasLiked) {
                        $like = $likeManager->getLikeByUserAndDocumentary($user, $documentary);
                        $likeManager->unlikeDocumentary($like);
                        $activityManager->removeActivity($activity);
                    }
                }
            }
        }

        $headers = array(
            'Content-Type' => 'application/json'
        );
        $response = array("code" => 100, "success" => true, "error" => "");
        return new Response(json_encode($response), 200, $headers);
    }

    /**
     * @return DocumentaryService
     */
    private function getDocumentaryService()
    {
        return $this->container->get(DocumentaryServices::DOCUMENTARY);
    }

    /**
     * @return LikeService
     */
    private function getLikeService()
    {
        return $this->container->get(LikeServices::LIKE);
    }

    /**
     * @return ActivityService
     */
    private function getActivityService()
    {
        return $this->container->get(ActivityServices::ACTIVITY);
    }
}