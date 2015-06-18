<?php

namespace DW\DocumentaryBundle\Extension;

use DW\DocumentaryBundle\Service\DocumentaryService;

class DurationWidgetExtension extends \Twig_Extension
{
    /**
     * @var DocumentaryService $documentaryService
     */
    private $documentaryService;

    /**
     * @param DocumentaryService $documentaryService
     */
    public function __construct(DocumentaryService $documentaryService)
    {
        $this->documentaryService = $documentaryService;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'durationWidget' => new \Twig_Function_Method($this, 'durationWidget')
        );
    }

    /**
     * @return mixed
     */
    public function durationWidget()
    {
        $duration = $this->documentaryService->getDurations();
        return $duration;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'durationWidgetExtension';
    }
}