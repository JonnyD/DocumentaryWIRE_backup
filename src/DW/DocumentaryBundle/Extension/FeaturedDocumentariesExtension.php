<?php

namespace DW\DocumentaryBundle\Extension;

use Doctrine\Common\Collections\ArrayCollection;
use DW\DocumentaryBundle\Entity\Documentary;
use DW\DocumentaryBundle\Service\DocumentaryService;

class FeaturedDocumentariesExtension extends \Twig_Extension
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
            'featuredDocumentaries' => new \Twig_Function_Method($this, 'featuredDocumentaries')
        );
    }

    /**
     * @return ArrayCollection|Documentary[]
     */
    public function featuredDocumentaries()
    {
        $documentaries = $this->documentaryService->getFeaturedDocumentaries();
        return $documentaries;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'featuredDocumentariesExtension';
    }
}