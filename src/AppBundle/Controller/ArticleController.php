<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class ArticleController extends Controller
{
    /**
     * @Route("/", defaults={"page": "1", "_format"="html"}, name="home")
     * @Route("/page/{page}", defaults={"_format"="html"}, requirements={"page": "[1-9]\d*"}, name="home_paginated")
     * @Method("GET")
     */
    public function indexAction($page)
    {
        if ($page < 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        //Je récupère le nombre d'articles à afficher sur une page
        $nbPerPage = Article::NUM_ITEMS;

        // On récupère notre objet Paginator
        $listArticles = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Article')
            ->getArticlesPaginated($page, $nbPerPage);

        // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
        $nbPages = ceil(count($listArticles) / $nbPerPage);

        // Si la page n'existe pas, on retourne une 404
        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        // On donne toutes les informations nécessaires à la vue
        return $this->render(
            'Article/index.html.twig',
            array(
                'listArticles' => $listArticles,
                'nbPages' => $nbPages,
                'page' => $page,
            )
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/add", name="add")
     */
    public function addAction(Request $request)
    {
        $Article = new Article();
        $form = $this->createForm(ArticleType::class, $Article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Article);
            $em->flush();

            $request->getSession()->getFlashbag()->add('success', 'Le nouveau chapitre a bien été enregistré.');

            return $this->redirectToRoute('view_article', array('slug' => $Article->getSlug()));
        }

        return $this->render(
            'article/add.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route("/view/{slug}", name="view_article")
     * @Method("GET")
     */
    public function viewAction(Article $Article)
    {
        // $Article est donc une instance de AppBundle\Entity\Article
        // ou null si il n'existe pas, d'où ce if :
        if (null === $Article) {
            throw new NotFoundHttpException("Cet article n'existe pas !");
        }

        return $this->render(
            'article/view.html.twig',
            array(
                'article' => $Article,
            )
        );
    }

    /**
     * Displays a form to edit an existing Article entity.
     *
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit")
     */
    public function editAction(Article $article, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Article modifié avec succès');

            return $this->redirectToRoute('edit', ['id' => $article->getId()]);
        }

        return $this->render(
            'Article/edit.html.twig',
            [
                'article' => $article,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Export Article to PDF
     * @Route("/{id}/pdf", requirements={"id": "\d+"}, name="articletopdf")
     */
    public function pdfAction(Article $article)
    {
        $html = $this->renderView('Article/articleToPdf.html.twig', ['article' => $article,]);
        $htmltopdf = new \HTML2PDF('P', 'A4', 'fr', array(50, 50, 50, 50));
        $htmltopdf->pdf->SetDisplayMode('real');
        $htmltopdf->writeHTML($html);
        $htmltopdf->Output($article->getSlug().'.pdf');

        return new Response();

    }

    /**
     * Export Article to PDF
     * @Route("/bookpdf", name="booktopdf")
     */
    public function bookToPdfAction()
    {
        $listArticles = $this->getDoctrine()->getManager()->getRepository('AppBundle:Article')->findAllAsc();
        $html = $this->renderView('Article/booktopdf.html.twig', ['listArticles' => $listArticles,]);
        $htmltopdf = new \HTML2PDF('P', 'A4', 'fr', array(50, 50, 50, 50));
        $htmltopdf->pdf->SetDisplayMode('real');
        $htmltopdf->writeHTML($html);
        $htmltopdf->Output('billet-simple-pour-l-alaska.pdf');

        return new Response();


    }


    /**
     * @Route("/{id}/delete", name="delete")
     *
     */
    public function deleteAction(Article $article)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        $this->addFlash('success', 'Article Supprimé avec succès');

        return $this->redirectToRoute('home');
    }
}
