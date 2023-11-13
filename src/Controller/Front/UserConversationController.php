<?php

namespace App\Controller\Front;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/message-prive', name: 'app_conversation_')]
class UserConversationController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('front/userConversation/index.html.twig', [
            'controller_name' => 'UserConversationController',
        ]);
    }

    #[Route('/ma-discussion/check', name: 'check', methods: ['GET'])]
    public function checkUser(): Response
    {
        $user = $this->getUser();
        if ($user) {
            return $this->redirectToRoute('app_conversation_chat', [
                'id' => $user->getId(),
                'token' => $user->getUserConversation()->getToken(),
            ]);
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/ma-discussion/{id}/{token}', name: 'chat', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    #[IsGranted('', subject: 'user')]
    public function chat(
        User $user,
    ): Response {
        return $this->render('front/userConversation/chat.html.twig', [
            'controller_name' => 'UserConversationController',
        ]);
    }
}
