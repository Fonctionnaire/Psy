<?php

namespace App\Service\Email\DonationMail;

interface DonationMailToUserInterface
{
    public function __invoke($donation): void;
}
