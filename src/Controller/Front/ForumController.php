<?php

namespace App\Controller\Front;

use App\Entity\ForumAnswer;
use App\Entity\ForumCategory;
use App\Entity\ForumSubject;
use App\Form\ForumAnswerType;
use App\Form\ForumSubjectType;
use App\Repository\ForumAnswerRepository;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumSubjectRepository;
use App\Service\Email\ForumAnswerMailInterface;
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
    protected const ANSWER_PER_PAGE = 15;

    #[Route('/sujets', name: 'index', methods: ['GET'])]
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

    #[Route('/sujet/{slug}', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        ForumSubject $forumSubject,
        ForumSubjectRepository $forumSubjectRepository,
        ForumAnswerRepository $forumAnswerRepository,
        Request $request,
        ForumPaginatorInterface $forumPaginator,
        ForumAnswerMailInterface $forumAnswerMail
    ): Response {
        if ($this->getUser() !== $forumSubject->getAuthor()) {
            $forumSubject->setNbOfView($forumSubject->getNbOfView() + 1);
            $forumSubjectRepository->save($forumSubject, true);
        }

        $answer = new ForumAnswer();
        $form = $this->createForm(ForumAnswerType::class, $answer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (null === $this->getUser()) {
                $this->addFlash('warning', 'Vous devez être connecté pour répondre à un sujet');

                return $this->redirectToRoute('app_login');
            }
            $answer->setAuthor($this->getUser());
            $answer->setForumSubject($forumSubject);
            $trimMessage = preg_replace('/(<div>\s*)(<br\s*\/?>\s*)+|(<br\s*\/?>\s*)+(<\/div>)/i', '$1$4', $answer->getContent());
            $answer->setContent($trimMessage);
            $forumAnswerRepository->save($answer, true);

            $forumAnswerMail->send($this->getUser(), $forumSubject);

            $this->addFlash('success', 'Votre réponse a bien été envoyée');

            return $this->redirectToRoute('app_forum_show', [
                'slug' => $forumSubject->getSlug(),
            ]);
        }

        return $this->render('front/forum/show.html.twig', [
            'forumSubject' => $forumSubject,
            'answers' => $forumPaginator($forumAnswerRepository->findBy(['forumSubject' => $forumSubject, 'isBan' => false], ['createdAt' => 'ASC']), self::ANSWER_PER_PAGE),
            'form' => $form,
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

    #[Route('/sujet/{slug}/signaler', name: 'report_subject', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function reportSubject(ForumSubject $forumSubject, ForumSubjectRepository $forumSubjectRepository): Response
    {
        $forumSubject->setIsReported(true);
        $forumSubjectRepository->save($forumSubject, true);

        $this->addFlash('success', 'Le sujet a bien été signalé');

        return $this->redirectToRoute('app_forum_show', [
            'slug' => $forumSubject->getSlug(),
        ]);
    }

    #[Route('/reponse/{id}/signaler', name: 'report_answer', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function reportAnswer(ForumAnswer $forumAnswer, ForumAnswerRepository $forumAnswerRepository): Response
    {
        $forumAnswer->setIsReported(true);
        $forumAnswerRepository->save($forumAnswer, true);

        $this->addFlash('success', 'La réponse a bien été signalée');

        return $this->redirectToRoute('app_forum_show', [
            'slug' => $forumAnswer->getForumSubject()->getSlug(),
        ]);
    }
}
