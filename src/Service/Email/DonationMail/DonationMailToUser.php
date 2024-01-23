<?php

namespace App\Service\Email\DonationMail;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class DonationMailToUser implements DonationMailToUserInterface
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function __invoke($donation): void
    {
        $email = (new TemplatedEmail())
            // TODO: Change this email address
            ->from('nepasrepondre@anxiete-panique.fr')
            ->to($donation->getEmail())
            ->subject('Merci ! Votre don a été envoyé avec succès.')
            ->htmlTemplate('email/donation.html.twig')

            ->context([
                'donation' => $donation,
            ])
        ;

        $this->mailer->send($email);
    }
}