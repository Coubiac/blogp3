<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class ArticleController extends Controller
{
    /**
     * @Route("/", defaults={"page": "1", "_format"="html"}, name="home")
     * @Route("/page/{page}", defaults={"_format"="html"}, requirements={"page": "[1-9]\d*"}, name="home_paginated")
     * @Method("GET")
     */
    public function indexAction($page, $_format)
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findLatest($page);

        return $this->render('Article/index.'.$_format.'.twig', ['articles' => $articles]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/add", name="add")
     */
    public function addAction(Request $request)
    {
        $Article = new Article();
        $form = $this->createForm(ArticleType::class, $Article)
            ->add('save', SubmitType::class);
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
        // ou null si l'id $id  n'existe pas, d'où ce if :
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
        $form = $this->createForm(ArticleType::class, $article)->add('save', SubmitType::class);
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
     * @Route("/{id}/delete", name="delete")
     *
     */
    public function deleteAction(Request $request, Article $article)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        $this->addFlash('success', 'Article Supprimé avec succès');

        return $this->redirectToRoute('home');
    }
}
