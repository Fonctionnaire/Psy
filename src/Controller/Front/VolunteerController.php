<?php

namespace App\Controller\Front;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/benevoles', name: 'app_volunteer_')]
class VolunteerController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(UserRepository $userRepository)
    {
        return $this->render('front/volunteer/index.html.twig', [
            'volunteers' => $userRepository->findBy(['isVolunteer' => true]),
        ]);
    }
}
