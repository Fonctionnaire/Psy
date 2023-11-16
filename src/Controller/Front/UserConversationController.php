<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Entity\UserMessage;
use App\Form\UserMessageType;
use App\Security\Handler\UserConversationHandlerInterface;
use App\Service\Paginator\ConversationPaginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/message-prive', name: 'app_conversation_')]
class UserConversationController extends AbstractController
{
    public const MESSAGE_PER_PAGE = 10;

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
        Request $request,
        UserConversationHandlerInterface $userConversationHandler,
        ConversationPaginator $conversationPaginator
    ): Response {
        $userMessage = new UserMessage();
        $form = $this->createForm(UserMessageType::class, $userMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userConversationHandler($user, $userMessage);

            return $this->redirectToRoute('app_conversation_chat', [
                'id' => $user->getId(),
                'token' => $user->getUserConversation()->getToken(),
            ]);
        }

        // TODO Pagination et EMAIL
        return $this->render('front/userConversation/chat.html.twig', [
            'user' => $user,
            'messages' => $conversationPaginator($userMessage, $user, self::MESSAGE_PER_PAGE),
            'form' => $form,
        ]);
    }
}
