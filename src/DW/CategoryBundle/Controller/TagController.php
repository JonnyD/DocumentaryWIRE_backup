<?php

namespace DW\CategoryBundle\Controller;

use DW\CategoryBundle\Service\CategoryServices;
use DW\DocumentaryBundle\Service\DocumentaryServices;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use DW\CategoryBundle\Service\TagService;
use DW\DocumentaryBundle\Service\DocumentaryService;

class TagController extends Controller
{
    public function listTagsWidgetAction()
    {
        $tagService = $this->getTagService();
        $tags = $tagService->getAllTags();

        return $this->render('TagBundle:Tag:listTagsWidget.html.twig', array(
            'tags' => $tags
        ));
    }

    public function showTagAction($slug, Request $request)
    {
        $tagService = $this->getTagService();
        $tag = $tagService->getTagBySlug($slug);

        $documentaryService = $this->getDocumentaryService();
        $documentaries = $documentaryService->getDocumentariesByTag($tag);

        $paginateManager = $this->get('documentary_wire.paginate_manager');
        $documentaries = $paginateManager->paginate($documentaries, 15, $request);

        return $this->render('DocumentaryWIREBundle:Tag:show.html.twig', array(
            'documentaries' => $documentaries,
            'tag' => $tag
        ));
    }

    /**
     * @return TagService
     */
    private function getTagService()
    {
        return $this->container->get(CategoryServices::TAG);
    }

    /**
     * @return DocumentaryService
     */
    private function getDocumentaryService()
    {
        return $this->container->get(DocumentaryServices::DOCUMENTARY);
    }
}