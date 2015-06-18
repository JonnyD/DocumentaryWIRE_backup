<?php

namespace DW\BaseBundle\Extension;

use DW\DocumentaryBundle\Entity\DocumentaryFilter;
use DW\BaseBundle\Entity\Order;
use DW\DocumentaryBundle\Service\DocumentaryService;

class SectionExtension extends \Twig_Extension
{
    /**
     * @var DocumentaryService $documentaryService
     */
    private $documentaryService;

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
            'getDocumentariesBySection' => new \Twig_Function_Method($this, 'getDocumentariesBySection')
        );
    }

    /**
     * @param $section
     * @return array
     */
    public function getDocumentariesBySection($section)
    {
        $documentaries = array();

        switch ($section) {
            case 'recent':
                $documentaries = $this->documentaryService->getPublishedDocumentaryKeys(DocumentaryFilter::DATE, Order::DESC);
                break;
            case 'liked':
                $documentaries = $this->documentaryService->getPublishedDocumentaryKeys(DocumentaryFilter::LIKES, Order::DESC);
                break;
            case 'popular':
                $documentaries = $this->documentaryService->getPublishedDocumentaryKeys(DocumentaryFilter::VIEWS, Order::DESC);
                break;
            case 'discussed':
                $documentaries = $this->documentaryService->getPublishedDocumentaryKeys(DocumentaryFilter::COMMENTS, Order::DESC);
                break;
        }

        return $documentaries;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sectionExtension';
    }
}