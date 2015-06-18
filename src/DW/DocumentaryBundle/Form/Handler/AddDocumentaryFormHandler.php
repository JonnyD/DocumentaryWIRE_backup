<?php

namespace DW\DocumentaryBundle\Form\Handler;

use DW\DocumentaryBundle\Service\DocumentaryManager;
use DW\DocumentaryBundle\Service\LinkManager;
use DW\DWBundle\Service\SourceManager;

class AddDocumentaryFormHandler
{
    private $documentaryManager;
    private $linkManager;
    private $sourceManager;

    public function __construct(DocumentaryManager $documentaryManager, LinkManager $linkManager, SourceManager $sourceManager)
    {
        $this->documentaryManager = $documentaryManager;
        $this->linkManager = $linkManager;
        $this->sourceManager = $sourceManager;
    }

    public function handle(FormInterface $form, Request $request)
    {
        if (!$request->isMethod('POST'))
        {
            return false;
        }

        $form->bind($request);

        if (!$form->isValid())
        {
            return false;
        }

        $data = $form->getData();
        $title = $data['title'];
        $slug = $data['slug'];
        $description = $data['description'];
        $excerpt = $data['length'];
        $status = $data['status'];
        $source = $data['source'];
        $videoId = $data['videoId'];

        $documentary = $this->documentaryManager->createDocumentary($title, $slug, $description, $excerpt, $status);
        $this->documentaryManager->persist($documentary);

        $source = $this->sourceManager->getSourceByName($source);
        if ($source != null && $documentary != null)
        {
            $link = $this->linkManager->createLink($source, $videoId, $documentary);
            $this->linkManager->persist($link);
        }
    }
}