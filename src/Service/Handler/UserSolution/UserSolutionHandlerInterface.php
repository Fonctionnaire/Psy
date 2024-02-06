<?php

namespace App\Service\Handler\UserSolution;

use App\Entity\UserSolution;

interface UserSolutionHandlerInterface
{
    public function __invoke(UserSolution $userSolution): array;
}
