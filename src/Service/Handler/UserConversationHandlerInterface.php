<?php

namespace App\Service\Handler;

use App\Entity\User;
use App\Entity\UserMessage;

interface UserConversationHandlerInterface
{
    public function __invoke(User $user, UserMessage $userMessage): void;
}
