<?php

namespace DW\CategoryBundle\Extension;

use Doctrine\Common\Collections\ArrayCollection;
use DW\CategoryBundle\Entity\Tag;
use DW\CategoryBundle\Service\TagService;

class TagsWidgetExtension extends \Twig_Extension
{
    /**
     * @var TagService $tagService
     */
    private $tagService;

    /**
     * @param TagService $tagService
     */
    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'listTags' => new \Twig_Function_Method($this, 'listTags')
        );
    }

    /**
     * @return ArrayCollection|Tag[]
     */
    public function listTags()
    {
        $tags = $this->tagService->getAllTags();
        return $tags;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'listTagsExtension';
    }
}