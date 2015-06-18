<?php

namespace DW\DocumentaryBundle\Controller;

use DW\CategoryBundle\Entity\Category;
use DW\CategoryBundle\Service\CategoryServices;
use DW\CommentBundle\Service\CommentServices;
use DW\DocumentaryBundle\Entity\DocumentaryFilter;
use DW\DocumentaryBundle\Entity\DocumentaryStatus;
use DW\BaseBundle\Entity\Order;
use DW\DocumentaryBundle\Service\DocumentaryServices;
use DW\UserBundle\Entity\Status;
use DW\DocumentaryBundle\Form\AddDocumentary;
use DW\CommentBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DW\CommentBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use DW\DocumentaryBundle\Service\DocumentaryService;
use DW\CommentBundle\Service\CommentService;
use DW\CategoryBundle\Service\CategoryService;

class DocumentaryController extends Controller
{
	public function showAction($slug, $page, Request $request)
	{
        $sort = $request->query->get('sort');

        $documentaryService = $this->getDocumentaryService();
        $documentary = $documentaryService->getDocumentaryBySlug($slug);

        if ($documentary == null) {
            $truncated = $documentaryService->getDocumentaryByTruncatedSlug($slug);
            if ($truncated != null) {
                return $this->redirect($this->generateUrl('documentary_wire_show_documentary',
                    array('slug' => $truncated->getSlug())));
            }
        }

        if ($documentary == null) {
            throw $this->createNotFoundException("Documentary doesn't exist");
        }

        $embedCode = $documentaryService->getEmbedCode(
            $documentary->getVideoSource(),
            $documentary->getVideoId(),
            620, 465, true);

        $commentService = $this->getCommentService();
        if ($sort == "date") {
            $comments = $commentService->getParentCommentsByDocumentaryOrderedByDate($documentary);
        } else if ($sort == "points") {
            $comments = $commentService->getParentCommentsByDocumentaryOrderedByPoints($documentary);
        } else {
            $comments = $commentService->getParentCommentsByDocumentaryOrderedByPoints($documentary);
        }
        $commentCount = count($comments);
        $limitPerPage = 10;
        $pages = $commentCount / $limitPerPage;
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
        $comments = $paginateManager->getPaginator()->paginate(
            $comments,
            $page/*page number*/,
            $limitPerPage/*limit per page*/
        );
        $comments->setUsedRoute('documentary_wire_show_documentary_page');

        $comment = new Comment();
        $form = $this->createForm(new CommentType(), $comment);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            if ($form->isValid())
            {
                $userHelper = $this->get('documentary_wire.user_helper');
                $loggedInUser = $userHelper->getLoggedInUser();

                $comment->setDocumentary($documentaryService->merge($documentary));
                $comment->setCreated(new \DateTime());
                $comment->setStatus(Status::ACTIVE);
                $comment->setUser($loggedInUser);

                $commentService = $this->get('documentary_wire.comment_manager');
                $commentService->addComment($comment);

                $cacheHelper = $this->get('documentary_wire.cache_helper');
                $cacheHelper->deleteFromCache("documentary_slug_".$documentary->getSlug(), "comments");

                return $this->redirect($this->generateUrl('documentary_wire_show_documentary',
                    array('slug' => $documentary->getSlug())));
            }
        }

		return $this->render('DocumentaryBundle:Documentary:show.html.twig', array(
            'documentary' => $documentary,
            'embedCode' => $embedCode,
            'comments' => $comments,
            'commentCount' => $commentCount,
            'commentForm' => $form->createView(),
            'previousPage' => $previousPage,
            'nextPage' => $nextPage
		));
	}

    public function browseAction($page, Request $request)
    {
        $orderBy = $request->query->get('orderBy');
        $order = $request->query->get('order');

        $documentaryService = $this->getDocumentaryService();
        $documentaries = $documentaryService->getPublishedDocumentaryKeys($orderBy, $order);

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
        $documentaries->setUsedRoute('documentary_wire_browse_page');

        return $this->render(
            'DocumentaryBundle:Documentary:browse.html.twig', array(
                'documentaries' => $documentaries,
                'orderBy' => $orderBy,
                'order' => $order,
                'previousPage' => $previousPage,
                'nextPage' => $nextPage
            ));
    }

    public function showYearAction($year, Request $request)
    {
        $orderBy = $request->query->get('orderBy');
        $order = $request->query->get('order');

        $documentaryService = $this->getDocumentaryService();
        $documentaries = $documentaryService->getPublishedDocumentaryKeysByYear($year, $orderBy, $order);

        $paginateManager = $this->get('documentary_wire.paginate_manager');
        $documentaries = $paginateManager->paginate($documentaries, 12, $request);

        return $this->render(
            'DocumentaryBundle:Documentary:year.html.twig', array(
            'year' => $year,
            'documentaries' => $documentaries,
            'orderBy' => $orderBy,
            'order' => $order
        ));
    }

    public function listAction()
    {
        $categoryService = $this->getCategoryService();
        $categories = $categoryService->getCategoriesWithDocumentaries();

        $documentaryService = $this->getDocumentaryService();

        $list = array();
        foreach ($categories as $category) {
            $documentaries = $documentaryService->getDocumentariesByCategory($category,
                DocumentaryStatus::PUBLISH, DocumentaryFilter::TITLE, Order::ASC, -1);
            $list[$category->getName()]['name'] = $category->getName();
            $list[$category->getName()]['documentaries'] = $documentaries;
        }

        return $this->render('DocumentaryBundle:Documentary:documentary-list.html.twig', array(
            'list' => $list
        ));
    }

    public function listByCategoryAction(Category $category)
    {
        $documentaryService = $this->getDocumentaryService();
        $documentaries = $documentaryService->getDocumentariesByCategoryFromCache($category,
            DocumentaryStatus::PUBLISH, DocumentaryFilter::TITLE, Order::ASC, -1, 'list');

        return $this->render('DocumentaryBundle:Documentary:list.html.twig', array(
            'documentaries' => $documentaries
        ));
    }

    public function listYearsAction()
    {
        $documentaryService = $this->getDocumentaryService();
        $years = $documentaryService->getYears();

        return $this->render('DocumentaryBundle:Documentary:listYears.html.twig', array(
            'years' => $years
        ));
    }

    public function listDurationAction()
    {
        $documentaryService = $this->getDocumentaryService();
        $durations = $documentaryService->getDurations();

        return $this->render('DocumentaryBundle:Documentary:listDuration.html.twig', array(
            'durations' => $durations
        ));
    }

    public function showDurationAction($length, Request $request)
    {
        $orderBy = $request->query->get('orderBy');
        $order = $request->query->get('order');

        $documentaryService = $this->getDocumentaryService();
        $documentaries = $documentaryService->getPublishedDocumentaryKeysByDuration($length, $orderBy, $order);

        $paginateManager = $this->get('documentary_wire.paginate_manager');
        $documentaries = $paginateManager->paginate($documentaries, 12, $request);

        return $this->render('DocumentaryBundle:Documentary:showDuration.html.twig', array(
            'documentaries' => $documentaries,
            'length' => $length
        ));
    }

    public function showSliderAction()
    {
        $documentaryService = $this->getDocumentaryService();
        $documentaries = $documentaryService->getDocumentaryKeys(DocumentaryStatus::PUBLISH,
            DocumentaryFilter::DATE, Order::DESC, -1, "slider");

        return $this->render(
            'DocumentaryBundle:Documentary:slider.html.twig', array(
            'randomDocumentaries' => $documentaries
        ));
    }
    
    public function addAction()
    {
        $form = $this->createForm(new AddDocumentary());
    	
    	return $this->render('DocumentaryBundle:Documentary:add.html.twig', array(
    	    'form' => $form->createView(),
    	));
    }

    /**
     * @return DocumentaryService
     */
    private function getDocumentaryService()
    {
        return $this->container->get(DocumentaryServices::DOCUMENTARY);
    }

    /**
     * @return CommentService
     */
    private function getCommentService()
    {
        return $this->container->get(CommentServices::COMMENT);
    }

    /**
     * @return CategoryService
     */
    private function getCategoryService()
    {
        return $this->container->get(CategoryServices::CATEGORY);
    }
}
