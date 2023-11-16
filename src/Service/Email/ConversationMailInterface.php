<?php

namespace App\Service\Email;

use App\Entity\User;

interface ConversationMailInterface
{
    public function sendToAdmin(User $user): void;

    public function sendToUser(User $user): void;
}
