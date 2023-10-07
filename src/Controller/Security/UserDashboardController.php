<?php

namespace App\Controller\Security;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/utilisateur/mon-compte/{id}', name: 'app_user_dashboard_', requirements: ['id' => '\d+'])]
#[IsGranted('ROLE_USER')]
class UserDashboardController extends AbstractController
{

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(User $user): Response
    {
        return $this->render('security/dashboard/index.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/mes-victoires', name: 'victory', methods: ['GET'])]
    public function victory(): Response
    {
        return $this->render('security/dashboard/victory.html.twig');
    }

}