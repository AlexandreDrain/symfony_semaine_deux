<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditRoleUserType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @param UserRepository $users
     * @return Response
     */
    public function liste(UserRepository $users): Response
    {
        $repository = $users;
        $users = $repository->findAll();

        return  $this->render('user/liste.html.twig', ['users' => $users]);
    }

    /**
     * @param User $user
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function changeRole(User $user, Request $request, ObjectManager $manager): Response
    {
        $form = $this->createForm(EditRoleUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', 'Vous avez bien modfié un role');
            return $this->redirectToRoute('app_user_liste');
        }

        return $this->render('admin/user/change-role.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
