<?php

namespace App\Service\Email;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;

class ConversationMail implements ConversationMailInterface
{
    public function __construct(private readonly MailerInterface $mailer, private readonly UserRepository $userRepository, private readonly RequestStack $requestStack)
    {
    }

    public function sendToAdmin(User $user): void
    {
        $host = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();

        $admins = $this->userRepository->findByRole('ROLE_ADMIN');

        $adminEmails = [];
        foreach ($admins as $admin) {
            $adminEmails[] = $admin->getEmail();
        }

        if (empty($adminEmails)) {
            return;
        }
        $email = (new TemplatedEmail())
            ->from('nepasrepondre@anxiete-panique.fr')
            ->to(...$adminEmails)
            ->subject('Nouveau Message privé')
            ->htmlTemplate('email/conversationMailToAdmin.html.twig')
            ->context([
                    'user' => $user,
                    'host' => $host,
                ]
            )
        ;

        $this->mailer->send($email);
    }

    public function sendToUser(User $user): void
    {
        $host = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();

        $email = (new TemplatedEmail())
            ->from('nepasrepondre@anxiete-panique.fr')
            ->to($user->getEmail())
            ->subject('Nouvelle réponse')
            ->htmlTemplate('email/conversationMailToUser.html.twig')
            ->context([
                    'user' => $user,
                    'host' => $host,
                ]
            )
        ;

        $this->mailer->send($email);
    }
}
