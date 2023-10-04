<?php

namespace App\Service\Email;

use App\Entity\User;

interface VerifyAccountMailInterface
{
    public function __invoke(User $user): void;
}