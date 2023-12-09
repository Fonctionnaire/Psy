<?php

namespace App\Controller\Front;

use App\Entity\ForumCategory;
use App\Entity\ForumSubject;
use App\Form\ForumSubjectType;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumSubjectRepository;
use App\Service\Paginator\ForumPaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/forum', name: 'app_forum_')]
class ForumController extends AbstractController
{
    protected const SUBJECT_PER_PAGE = 15;

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        ForumSubjectRepository $forumSubjectRepository,
        ForumCategoryRepository $forumCategoryRepository,
        ForumPaginatorInterface $forumPaginator
    ): Response {
        return $this->render('front/forum/index.html.twig', [
            'nbOfSubjects' => $forumSubjectRepository->count(['isBan' => false]),
            'subjects' => $forumPaginator($forumSubjectRepository->findBy(['isBan' => false], ['createdAt' => 'DESC']), self::SUBJECT_PER_PAGE),
            'categories' => $forumCategoryRepository->findAll(),
        ]);
    }

    #[Route('/categorie/{slug}', name: 'category', methods: ['GET'])]
    public function category(
        ForumCategory $category,
        ForumCategoryRepository $forumCategoryRepository,
        ForumSubjectRepository $forumSubjectRepository,
        ForumPaginatorInterface $forumPaginator
    ): Response {
        return $this->render('front/forum/category.html.twig', [
            'nbOfSubjects' => $forumSubjectRepository->count(['isBan' => false]),
            'categories' => $forumCategoryRepository->findAll(),
            'category' => $category,
            'subjects' => $forumPaginator($forumSubjectRepository->findBy(['forumCategory' => $category, 'isBan' => false], ['createdAt' => 'DESC']), self::SUBJECT_PER_PAGE),
        ]);
    }

    #[Route('/sujet/{slug}', name: 'show', methods: ['GET'])]
    public function show(
        ForumSubject $forumSubject,
        ForumSubjectRepository $forumSubjectRepository
    ): Response {
        if ($this->getUser() !== $forumSubject->getAuthor()) {
            $forumSubject->setNbOfView($forumSubject->getNbOfView() + 1);
            $forumSubjectRepository->save($forumSubject, true);
        }

        return $this->render('front/forum/show.html.twig', [
            'forumSubject' => $forumSubject,
        ]);
    }

    #[Route('/nouveau-sujet', name: 'new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(
        Request $request,
        ForumSubjectRepository $forumSubjectRepository,
    ): Response {
        $subject = new ForumSubject();
        $form = $this->createForm(ForumSubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subject->setAuthor($this->getUser());
            $slugger = new AsciiSlugger();
            $subject->setSlug(strtolower($slugger->slug($subject->getSubject())));
            $forumSubjectRepository->save($subject, true);

            $this->addFlash('success', 'Votre sujet a bien été créé');

            return $this->redirectToRoute('app_forum_show', [
                'slug' => $subject->getSlug(),
            ]);
        }

        return $this->render('front/forum/new.html.twig', [
            'form' => $form,
        ]);
    }
}
