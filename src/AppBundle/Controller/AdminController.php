<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\User;


class AdminController extends Controller
{
    /**
     * View all Comments
     * @Route("/admin/comments", name="adminComments")
     */
    public function viewComments()
    {
        $listcomments = $this->getDoctrine()->getRepository("AppBundle:Comment")->findAll();
        return $this->render('Admin/comments.html.twig', array('listComments' => $listcomments,));
    }

    /**
     * Delete Comments
     * @Route("/admin/{id}/delete", name="deleteComment")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteComment(Comment $comment)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($comment);
        $entityManager->flush();
        return $this->redirectToRoute('adminComments');
    }

    /**
     * View all Users
     *
     * @Route("/admin/users", name="adminUsers")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function viewUsers()
    {
        $userManager = $this->get('fos_user.user_manager');
        $listUsers = $userManager->findUsers();
        return $this->render('Admin/users.html.twig', array('listUsers' => $listUsers,));
    }

    /**
     * Delete Users
     *
     * @Route("/admin/users/{username}/delete", name="deleteUser")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteUser(User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('adminUsers');

    }

    /**
     * view all articles
     * @Route("/admin/articles", name="adminArticles")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function viewArticles()
    {
        $listArticles = $this->getDoctrine()->getRepository("AppBundle:Article")->findAll();
        return $this->render('Admin/articles.html.twig', array('listArticles' => $listArticles));
    }
}
