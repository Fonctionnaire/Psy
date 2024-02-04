<?php

namespace App\Service\Email\DonationMail;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class DonationMailToAdmin implements DonationMailToAdminInterface
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function __invoke($donation): void
    {
        $email = (new TemplatedEmail())
            // TODO: Change this email address
            ->from('nepasrepondre@anxiete-panique.fr')
            ->to('anxiete.panique@gmail.com')
            ->subject('Nouveau don')
            ->htmlTemplate('email/donationToMe.html.twig')
            ->context([
                'donation' => $donation,
            ])
        ;

        $this->mailer->send($email);
    }
}
