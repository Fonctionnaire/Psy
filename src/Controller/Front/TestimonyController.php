<?php

namespace App\Controller\Front;


use App\Entity\Testimony;
use App\Repository\TestimonyCategoryRepository;
use App\Repository\TestimonyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/temoignages', name: 'app_testimony_')]
class TestimonyController extends AbstractController
{

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(TestimonyRepository $testimonyRepository, TestimonyCategoryRepository $testimonyCategoryRepository): Response
    {
        return $this->render('front/testimony/index.html.twig', [
            'testimonies' => $testimonyRepository->findBy(['isValidated' => true], ['createdAt' => 'DESC']),
            'categories' => $testimonyCategoryRepository->findAll()
        ]);
    }

    #[Route('/{token}', name: 'show', methods: ['GET'])]
    public function show(Testimony $testimony): Response
    {
        if($testimony->isIsValidated() === false) {
            throw $this->createNotFoundException();
        }

        return $this->render('front/testimony/show.html.twig', [
            'testimony' => $testimony,
        ]);
    }
}