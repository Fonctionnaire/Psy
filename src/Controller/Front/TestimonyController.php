<?php

namespace App\Controller\Front;


use App\Entity\Testimony;
use App\Entity\TestimonyCategory;
use App\Form\TestimonyType;
use App\Repository\TestimonyCategoryRepository;
use App\Repository\TestimonyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

    #[Route('/show/{token}', name: 'show', methods: ['GET'])]
    public function show(Testimony $testimony): Response
    {
        if($testimony->isIsValidated() === false) {
            throw $this->createNotFoundException();
        }

        return $this->render('front/testimony/show.html.twig', [
            'testimony' => $testimony,
        ]);
    }

    #[Route('/categorie/{slug}', name: 'category_list', methods: ['GET'])]
    public function byCategory(
        TestimonyRepository $testimonyRepository,
        TestimonyCategoryRepository $testimonyCategoryRepository,
        TestimonyCategory $category
    ): Response
    {

        return $this->render('front/testimony/byCategory.html.twig', [
            'countTestimonies' => $testimonyRepository->count(['isValidated' => true]),
            'testimonies' => $testimonyRepository->findAllByCategory($category),
            'categories' => $testimonyCategoryRepository->findAll()
        ]);
    }

    #[Route('/nouveau-temoignage', name: 'new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(
        Request $request,
        TestimonyRepository $testimonyRepository
    ): Response
    {
        if($this->getUser()->getTestimony()) {
            $this->addFlash('warning', 'Vous avez déjà envoyé un témoignage');

            return $this->redirectToRoute('app_testimony_index');
        }

        $testimony = new Testimony();
        $form = $this->createForm(TestimonyType::class, $testimony);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $testimony->setUser($this->getUser());
            $testimonyRepository->save($testimony, true);

            $this->addFlash('success', 'Votre témoignage a bien été envoyé, il sera publié après validation par un administrateur');

            return $this->redirectToRoute('app_testimony_index');
        }

        return $this->render('front/testimony/new.html.twig', [
            'form' => $form
        ]);
    }
}