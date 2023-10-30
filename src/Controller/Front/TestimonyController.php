<?php

namespace App\Controller\Front;

use App\Entity\Testimony;
use App\Entity\TestimonyCategory;
use App\Form\TestimonyType;
use App\Repository\TestimonyCategoryRepository;
use App\Repository\TestimonyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/temoignages', name: 'app_testimony_')]
class TestimonyController extends AbstractController
{
    public const TESTIMONY_PER_PAGE = 10;

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        TestimonyRepository $testimonyRepository,
        TestimonyCategoryRepository $testimonyCategoryRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $testimonies = $testimonyRepository->findBy(['isValidated' => true], ['createdAt' => 'DESC']);

        $subjects = $paginator->paginate(
            $testimonies,
            $request->query->getInt('page', 1),
            self::TESTIMONY_PER_PAGE
        );

        $requestPage = (int) $request->get('page');

        if ($requestPage > ceil($subjects->getTotalItemCount() / self::TESTIMONY_PER_PAGE)) {
            return $this->redirectToRoute('app_testimony_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/testimony/index.html.twig', [
            'testimonies' => $subjects,
            'categories' => $testimonyCategoryRepository->findAll(),
        ]);
    }

    #[Route('/show/{token}', name: 'show', methods: ['GET'])]
    public function show(Testimony $testimony): Response
    {
        if (false === $testimony->isIsValidated()) {
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
        TestimonyCategory $category,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $testimonies = $testimonyRepository->findAllByCategory($category);

        $subjects = $paginator->paginate(
            $testimonies,
            $request->query->getInt('page', 1),
            self::TESTIMONY_PER_PAGE
        );

        $requestPage = (int) $request->get('page');

        if ($requestPage > ceil($subjects->getTotalItemCount() / self::TESTIMONY_PER_PAGE)) {
            return $this->redirectToRoute('app_testimony_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/testimony/byCategory.html.twig', [
            'countTestimonies' => $testimonyRepository->count(['isValidated' => true]),
            'testimonies' => $subjects,
            'categories' => $testimonyCategoryRepository->findAll(),
        ]);
    }

    #[Route('/nouveau-temoignage', name: 'new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(
        Request $request,
        TestimonyRepository $testimonyRepository
    ): Response {
        if ($this->getUser()->getTestimony()) {
            $this->addFlash('warning', 'Vous avez déjà envoyé un témoignage');

            return $this->redirectToRoute('app_testimony_index');
        }

        $testimony = new Testimony();
        $form = $this->createForm(TestimonyType::class, $testimony);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $testimony->setUser($this->getUser());
            $testimonyRepository->save($testimony, true);

            $this->addFlash('success', 'Votre témoignage a bien été envoyé, il sera publié après validation par un administrateur');

            return $this->redirectToRoute('app_testimony_index');
        }

        return $this->render('front/testimony/new.html.twig', [
            'form' => $form,
        ]);
    }
}
