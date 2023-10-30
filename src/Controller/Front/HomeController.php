<?php

namespace App\Controller\Front;

use App\Repository\TestimonyRepository;
use App\Repository\UserReviewRepository;
use App\Service\Home\HomeCountServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_home_')]
class HomeController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        UserReviewRepository $userReviewRepository,
        HomeCountServiceInterface $homeCountService,
        TestimonyRepository $testimonyRepository
    ): Response {
        return $this->render('front/home/index.html.twig', [
            'userReviews' => $userReviewRepository->findByIsValidated(),
            'homeCount' => $homeCountService->getHomeCount(),
            'lastTestimony' => $testimonyRepository->findLastTestimony(),
        ]);
    }
}
