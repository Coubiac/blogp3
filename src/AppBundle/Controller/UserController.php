<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\User;

class UserController extends Controller
{
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
     * Delete User
     *
     * @Route("/admin/users/{username}/delete", name="deleteUser")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteUserAction(User $user)
    {
        if (!$user->isSuperAdmin()) // On ne supprime pas les super admins
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('adminUsers');

    }

    /**
     * Promote User
     *
     * @Route("/admin/users/{username}/promote", name="promoteUser")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function promoteUser(User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        if (!$user->isSuperAdmin() AND !$user->hasRole("ROLE_ADMIN")) // Un super Admin a déja les permissions maximales
        {
            $user->addRole("ROLE_ADMIN");
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('adminUsers');
    }

    /**
     * Demote User
     *
     * @Route("/admin/users/{username}/demote", name="demoteUser")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function demoteUser(User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        if (!$user->isSuperAdmin()) // On ne touche pas aux super admins
        {
            $user->removeRole("ROLE_ADMIN");
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('adminUsers');
    }
}
