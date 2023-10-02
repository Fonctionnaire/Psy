<?php

namespace App\Service\Email;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class VerifyAccountMail implements VerifyAccountMailInterface
{

    public function __construct(private readonly MailerInterface $mailer, private readonly RequestStack $requestStack)
    {
    }

    public function __invoke(User $user): void
    {
        $host = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
        $email = (new TemplatedEmail())
            ->from('inscription@mypsycommunity.com')
            ->to($user->getEmail())
            ->subject('Confirmer votre inscription')
            ->htmlTemplate('email/verifyAccountEmail.html.twig')
            ->context([
                'user' => $user,
                'host' => $host
            ])
            ;
        $this->mailer->send($email);
    }
}