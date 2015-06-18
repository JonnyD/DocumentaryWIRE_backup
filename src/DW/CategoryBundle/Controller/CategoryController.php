<?php

namespace DW\CategoryBundle\Controller;

use DW\CategoryBundle\Service\CategoryServices;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DW\CategoryBundle\Service\CategoryService;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{   	
    public function listAction()
    {
    	$categoryService = $this->getCategoryService();
    	$categories = $categoryService->getAllCategories();
    	
    	return $this->render(
            'DocumentaryWIREBundle:Category:list.html.twig',
            array('categories' => $categories)
    	);
    }
    
    public function showAction($slug, $page, Request $request)
    {
        $orderBy = $request->query->get('orderBy');
        $order = $request->query->get('order');

        $categoryService = $this->getCategoryService();
    	$category = $categoryService->getCategoryBySlug($slug);

        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $documentaries = $documentaryManager->getPublishedDocumentaryKeysByCategory($category, $orderBy, $order);

        $totalCount = count($documentaries);
        $limitPerPage = 12;
        $pages = $totalCount / $limitPerPage;
        if ($page > $pages + 1) {
            throw $this->createNotFoundException("Page doesn't exist");
        }
        $previousPage = null;
        $nextPage = null;
        if ($page > 1) {
            $previousPage = $page - 1;
        }
        if ($page < $pages) {
            $nextPage = $page + 1;
        }

        $paginateManager = $this->get('documentary_wire.paginate_manager');
        $documentaries = $paginateManager->getPaginator()->paginate(
            $documentaries,
            $page/*page number*/,
            $limitPerPage/*limit per page*/
        );
        $documentaries->setUsedRoute('documentary_wire_show_category_page');
    	 
    	return $this->render(
            'DocumentaryWIREBundle:Category:show.html.twig', array(
                'category' => $category,
                'documentaries' => $documentaries,
                'orderBy' => $orderBy,
                'order' => $order,
                'previousPage' => $previousPage,
                'nextPage' => $nextPage
            )
    	);
    }

    /**
     * @return CategoryService
     */
    private function getCategoryService()
    {
        return $this->container->get(CategoryServices::CATEGORY);
    }
}
