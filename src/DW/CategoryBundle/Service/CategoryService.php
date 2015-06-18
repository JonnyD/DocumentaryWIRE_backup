<?php

namespace DW\CategoryBundle\Service;

use Doctrine\ORM\EntityManager;
use DW\CategoryBundle\Repository\CategoryRepository;

class CategoryService
{
	private $categoryRepository;

	public function __construct(CategoryRepository $categoryRepository)
	{
        $this->categoryRepository = $categoryRepository;
	}
	
	public function getAllCategories()
    {
		return $this->categoryRepository->findAllCategories();
	}

    public function getCategoriesWithDocumentaries()
    {
        return $this->categoryRepository->findCategoriesWithDocumentaries();
    }

    public function getCategoryByName($name)
    {
        return $this->categoryRepository->findOneByName($name);
    }

    public function getCategoryBySlug($slug)
    {
        return $this->categoryRepository->findOneBySlug($slug);
    }
}