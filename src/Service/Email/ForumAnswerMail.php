<?php

namespace App\Service\Email;

use App\Entity\ForumSubject;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;

class ForumAnswerMail implements ForumAnswerMailInterface
{

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly RequestStack $requestStack
    )
    {
    }

    public function send(User $user, ForumSubject $forumSubject): void
    {
        if($user !== $forumSubject->getAuthor()) {

            $host = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();

            $email = (new TemplatedEmail())
                ->from('nepasrepondre@anxiete-panique.fr')
                ->to($forumSubject->getAuthor()->getEmail())
                ->subject('Nouvelle réponse à votre sujet')
                ->htmlTemplate('email/forumAnswerMailToUser.html.twig')
                ->context([
                        'user' => $user,
                        'host' => $host,
                        'forumSubject' => $forumSubject,
                    ]
                );

            $this->mailer->send($email);
        }
    }
}