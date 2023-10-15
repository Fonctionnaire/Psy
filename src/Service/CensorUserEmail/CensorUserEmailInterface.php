<?php

namespace App\Service\CensorUserEmail;

interface CensorUserEmailInterface
{
    public function __invoke(string $email): string;
}