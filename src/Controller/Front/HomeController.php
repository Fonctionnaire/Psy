<?php

namespace App\Controller\Front;

use App\Repository\UserReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_home_')]
class HomeController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(UserReviewRepository $userReviewRepository): Response
    {
        return $this->render('front/home/index.html.twig', [
            'userReviews' => $userReviewRepository->findByIsValidated(),
        ]);
    }
}
