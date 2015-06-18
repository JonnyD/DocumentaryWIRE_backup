<?php

namespace DW\CategoryBundle\Service;

use Doctrine\ORM\EntityManager;
use DW\CategoryBundle\Repository\Doctrine\ORM\TagRepository;

class TagService
{
    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function getAllTags()
    {
        return $this->tagRepository->findAll();
    }

    public function getTagBySlug($slug)
    {
        return $this->tagRepository->findOneBy(
            array('slug' => $slug)
        );
    }
}