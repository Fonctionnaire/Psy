<?php

namespace App\Service\Email;

use App\Entity\User;

interface ResetPasswordMailInterface
{
    public function __invoke(User $user): void;
}