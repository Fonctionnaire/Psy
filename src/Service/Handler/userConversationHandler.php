<?php

namespace App\Service\Handler;

use App\Entity\User;
use App\Entity\UserMessage;
use App\Service\Email\ConversationMailInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class userConversationHandler extends AbstractController implements userConversationHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ConversationMailInterface $conversationMail,
    ) {
    }

    public function __invoke(User $user, UserMessage $userMessage): void
    {
        $userConversation = $user->getUserConversation();

        if ($this->isGranted('ROLE_ADMIN')) {
            $userConversation->setIsViewed(true);
            $userMessage->setIsAdmin(true);
        } else {
            $userConversation->setIsViewed(false);
        }

        $trimMessage = preg_replace('/(<div>\s*)(<br\s*\/?>\s*)+|(<br\s*\/?>\s*)+(<\/div>)/i', '$1$4', $userMessage->getContent());

        $userMessage->setContent($trimMessage);
        $userMessage->setUserConversation($userConversation);
        $userMessage->setUser($this->getUser());

        $this->em->persist($userMessage);
        $this->em->flush();

        if ($this->isGranted('ROLE_ADMIN')) {
            $this->conversationMail->sendToUser($user);
        } else {
            $this->conversationMail->sendToAdmin($user);
        }

        $this->addFlash('success', 'Votre message a bien été envoyé');
    }
}
