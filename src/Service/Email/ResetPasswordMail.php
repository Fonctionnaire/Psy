<?php

namespace App\Service\Email;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;

class ResetPasswordMail implements ResetPasswordMailInterface
{
    public function __construct(private readonly MailerInterface $mailer, private readonly RequestStack $requestStack)
    {
    }

    public function __invoke(User $user): void
    {
        $host = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
        $email = (new TemplatedEmail())
            ->from('nouveau-mdp@mypsycommunity.com')
            ->to($user->getEmail())
            ->subject('Réinitialiser votre mot de passe')
            ->htmlTemplate('email/resetPasswordEmail.html.twig')
            ->context([
                'user' => $user,
                'host' => $host
            ])
        ;
        $this->mailer->send($email);
    }
}