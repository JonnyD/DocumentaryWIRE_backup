<?php

namespace DW\CategoryBundle\Extension;

use DW\CategoryBundle\Service\CategoryService;

class CategoriesWidgetExtension extends \Twig_Extension
{
    /**
     * @var CategoryService $categoryService
     */
    private $categoryService;

    /**
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'listCategories' => new \Twig_Function_Method($this, 'listCategories')
        );
    }

    /**
     * @return mixed
     */
    public function listCategories()
    {
        $categories = $this->categoryService->getCategoriesWithDocumentaries();
        return $categories;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'listCategoriesExtension';
    }
}