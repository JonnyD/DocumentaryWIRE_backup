<?php

namespace DW\DocumentaryBundle\Extension;

use DW\DocumentaryBundle\Entity\Documentary;
use DW\DocumentaryBundle\Service\DocumentaryService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FetchDocumentaryExtension extends \Twig_Extension
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
            'fetchDocumentary' => new \Twig_Function_Method($this, 'fetchDocumentary')
        );
    }

    /**
     * @param $slug
     * @return Documentary
     */
    public function fetchDocumentary($slug)
    {
        $documentary = $this->documentaryService->getDocumentaryBySlug($slug);
        return $documentary;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fetchDocumentaryExtension';
    }
}