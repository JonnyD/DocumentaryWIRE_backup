<?php

namespace DW\DocumentaryBundle\Extension;

use Doctrine\Common\Collections\ArrayCollection;
use DW\DocumentaryBundle\Entity\Documentary;
use DW\DocumentaryBundle\Service\DocumentaryService;

class RelatedDocumentariesExtension extends \Twig_Extension
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
            'relatedDocumentaries' => new \Twig_Function_Method($this, 'relatedDocumentaries')
        );
    }

    /**
     * @param $documentary
     * @return ArrayCollection|Documentary[]
     */
    public function relatedDocumentaries($documentary)
    {
        $documentaries = $this->documentaryService->getRelatedDocumentaries($documentary);
        return $documentaries;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'relatedDocumentariesExtension';
    }
}