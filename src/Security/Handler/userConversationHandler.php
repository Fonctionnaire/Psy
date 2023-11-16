<?php

namespace App\Security\Handler;

use App\Entity\User;
use App\Entity\UserMessage;
use App\Repository\UserMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class userConversationHandler extends AbstractController implements userConversationHandlerInterface
{
    public function __construct(private readonly UserMessageRepository $userMessageRepository, private readonly EntityManagerInterface $em)
    {
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

        $trimMessage = preg_replace('/^(<p><br><\/p>)+|(<p><br><\/p>)+$/', '', $userMessage->getContent());

        $userMessage->setContent($trimMessage);
        $userMessage->setUserConversation($userConversation);
        $userMessage->setUser($this->getUser());

        //        $this->userMessageRepository->save($userMessage, true);

        $this->em->persist($userMessage);
        $this->em->flush();
        //        if($this->isGranted('ROLE_ADMIN')) {
        // //            $this->tchatMailToUser->__invoke($user);
        //        }else{
        // //            $this->tchatMailToAdmin->__invoke($user);
        //        }

        $this->addFlash('success', 'Votre message a bien été envoyé');
    }
}
