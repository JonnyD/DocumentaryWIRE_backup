<?php

namespace DW\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use DW\ActivityBundle\Service\ActivityService;

class CommunityController extends Controller
{
    public function showAction(Request $request)
    {
        $activityService = $this->getActivityService();
        $activity = $activityService->getActivityOrderedByDate();

        $paginateManager = $this->get('documentary_wire.paginate_manager');
        $activity = $paginateManager->paginate($activity, 32, $request);

        return $this->render('DocumentaryWIREBundle:Community:show.html.twig', array(
            'activity' => $activity
        ));
    }

    /**
     * @return ActivityService
     */
    private function getActivityService()
    {
        return $this->container->get('documentary_wire.activity_service');
    }
}