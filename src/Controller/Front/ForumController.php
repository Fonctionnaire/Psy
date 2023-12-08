<?php

namespace App\Controller\Front;

use App\Entity\ForumSubject;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumSubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/forum', name: 'app_forum_')]
class ForumController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(ForumSubjectRepository $forumSubjectRepository, ForumCategoryRepository $forumCategoryRepository): Response
    {
        return $this->render('front/forum/index.html.twig', [
            'subjects' => $forumSubjectRepository->findBy(['isBan' => false], ['createdAt' => 'DESC']),
            'categories' => $forumCategoryRepository->findAll(),
        ]);
    }

    #[Route('/sujet/{slug}', name: 'show', methods: ['GET'])]
    public function show(ForumSubject $forumSubject, ForumSubjectRepository $forumSubjectRepository): Response
    {
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
    public function new(Request $request, ForumSubjectRepository $forumSubjectRepository): Response
    {
        return $this->render('front/forum/new.html.twig', [
        ]);
    }
}
