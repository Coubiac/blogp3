<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;


class AboutController extends Controller
{
    /**
     * @return Response
     * @Route("/about/author")
     */
    public function authorAction()
    {
        $author = "Benoit Grisot";

        return $this->render('about/author.html.twig', array('author' => $author,));
    }
}
