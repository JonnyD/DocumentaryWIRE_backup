<?php

namespace DW\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SearchController extends Controller
{
    public function searchAction()
    {
        return $this->render(
            'DocumentaryWIREBundle:Search:search.html.twig'
        );
    }
}