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
use Symfony\Component\HttpFoundation\Session\Session;


class ArticleController extends Controller
{
    /**
     * @return Response
     * @Route("/")
     */
    public function indexAction()
    {
        return new Response('<html><body>Hello, my name is Benoit</body></html>');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/add")
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

            $request->getSession()->getFlashbag()->add('success', 'Annonce bien enregistrée.');

            return $this->redirectToRoute('view_article', array('id' => $Article->getId()));
        }

        return $this->render(
            'article/add.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route("/view/{id}", name="view_article", requirements={"id": "\d+"})
     */
    //TODO-me mettre en place une jolie URL
    public function viewAction($id)
    {
        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Article');

        // On récupère l'entité correspondante à l'id $id
        $Article = $repository->find($id);

        // $Article est donc une instance de AppBundle\Entity\Article
        // ou null si l'id $id  n'existe pas, d'où ce if :
        if (null === $Article) {
            throw new NotFoundHttpException("L'Article d'id ".$id." n'existe pas.");
        }

        return $this->render(
            'article/view.html.twig',
            array(
                'article' => $Article,
            )
        );
    }
}
