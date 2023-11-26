<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mmd-admin/volunteer', name: 'app_admin_volunteer_')]
class VolunteerAdminController extends AbstractController
{

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/volunteer/index.html.twig', [
            'users' => $userRepository->findBy(['isAccountValidated' => true], ['createdAt' => 'DESC'])
        ]);
    }
}