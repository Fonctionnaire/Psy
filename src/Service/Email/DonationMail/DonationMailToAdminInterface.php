<?php

namespace App\Service\Email\DonationMail;

interface DonationMailToAdminInterface
{
    public function __invoke($donation): void;
}
