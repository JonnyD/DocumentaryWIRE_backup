<?php

namespace DW\ActivityBundle\Extension;

use DW\ActivityBundle\Service\ActivityService;

class ActivityWidgetExtension extends \Twig_Extension
{
    /** @var ActivityService $activityService */
    private $activityService;

    /**
     * @param ActivityService $activityService
     */
    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'activityWidget' => new \Twig_Function_Method($this, 'activityWidget')
        );
    }

    /**
     * @param $type
     * @return array
     */
    public function activityWidget($type)
    {
        if ($type == "comments") {
            $activity = $this->activityService->getRecentCommentsForWidget(5);
        } else if ($type == "likes") {
            $activity = $this->activityService->getRecentLikesForWidget(10);
        } else {
            $activity = $this->activityService->getRecentActivityForWidget();
        }
        return $activity;
    }

    public function getName()
    {
        return 'activityWidgetExtension';
    }
}