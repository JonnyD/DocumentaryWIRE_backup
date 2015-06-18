<?php

namespace DW\BaseBundle\Controller;

use DW\DocumentaryBundle\Entity\Documentary;
use DW\DocumentaryBundle\Service\DocumentaryServices;
use DW\DocumentaryBundle\Service\DocumentaryService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $documentaryManager = $this->getDocumentaryService();
        $featuredDocumentaries = $documentaryManager->getFeaturedDocumentaries();

        return $this->render('BaseBundle:Home:index.html.twig', array(
            'featuredDocumentaries' => $featuredDocumentaries
        ));
    }

    public function sandboxAction()
    {
        $documentary = new Documentary();
        $documentary->setTitle('test');

        $documentaryService = $this->getDocumentaryService();
        $documentaryService->addDocumentary($documentary);
    }

    /**
     * @return DocumentaryService
     */
    private function getDocumentaryService()
    {
        return $this->container->get(DocumentaryServices::DOCUMENTARY);
    }
}
