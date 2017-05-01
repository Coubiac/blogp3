<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{

    /**
     * Export all articles to PDF file
     * @Route("/bookpdf", name="booktopdf")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method("GET")
     */
    public function bookToPdfAction()
    {
        $listArticles = $this->getDoctrine()->getRepository('AppBundle:Article')->findAllAsc();
        $html = $this->renderView('Article/booktopdf.html.twig', ['listArticles' => $listArticles,]);
        $htmltopdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(10, 15, 10, 15));
        $htmltopdf->pdf->SetDisplayMode('real');
        $htmltopdf->writeHTML($html);
        $webroot = $this->getParameter('webroot');
        $htmltopdf->Output($webroot . '/billet-simple-pour-l-alaska.pdf', 'F');
        $response = array("code" => 100, "success" => true);

        return new Response(json_encode($response));
    }

    /**
     * View all Comments
     * @Route("/admin/comments", name="adminComments")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function viewCommentsAction()
    {
        $listcomments = $this->getDoctrine()->getRepository("AppBundle:Comment")->findAll();
        return $this->render('Admin/comments.html.twig', array('listComments' => $listcomments,));
    }

    /**
     * Delete Comments
     * @Route("/admin/{id}/delete", name="deleteComment")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteCommentAction(Comment $comment, Request $request)
    {
        $referer = $request->headers->get('referer');
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($comment);
        $entityManager->flush();
        return $this->redirect($referer);
    }

    /**
     * View all Users
     *
     * @Route("/admin/users", name="adminUsers")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function viewUsersAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $listUsers = $userManager->findUsers();
        return $this->render('Admin/users.html.twig', array('listUsers' => $listUsers,));
    }

    /**
     * Delete Users
     *
     * @Route("/admin/users/{username}/delete", name="deleteUser")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteUserAction(User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('adminUsers');

    }

    /**
     * view all articles
     * @Route("/admin/articles", name="adminArticles")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function viewArticlesAction()
    {
        $listArticles = $this->getDoctrine()->getRepository("AppBundle:Article")->findAll();
        return $this->render('Admin/articles.html.twig', array('listArticles' => $listArticles));
    }
}
