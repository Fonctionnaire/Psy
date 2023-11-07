<?php

namespace App\Service\Email;

use App\Entity\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;

class ContactMail implements ContactMailInterface
{
    public function __construct(private readonly MailerInterface $mailer, private readonly RequestStack $requestStack)
    {
    }

    public function sendToUser(Contact $contact): void
    {
        $host = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
        $email = (new TemplatedEmail())
            ->from('accuse-reception@mypsycommunity.com')
            ->to($contact->getEmail())
            ->subject('Nous avons bien reçu votre demande')
            ->htmlTemplate('email/receiptContactToUser.html.twig')
            ->context([
                'contact' => $contact,
                'host' => $host,
            ])
        ;
        $this->mailer->send($email);
    }

    public function sendToAdmin(Contact $contact): void
    {
        $host = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
        $email = (new TemplatedEmail())
            ->from('contact@mypsycommunity.com')
            ->to('thibaut.gruffy@gmail.com')
            ->subject('Nouveau message de contact')
            ->htmlTemplate('email/newContactToAdmin.html.twig')
            ->context([
                'contact' => $contact,
                'host' => $host,
            ])
        ;
        $this->mailer->send($email);
    }
}
