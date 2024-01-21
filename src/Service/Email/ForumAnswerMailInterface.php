<?php

namespace App\Service\Email;

use App\Entity\ForumSubject;
use App\Entity\User;

interface ForumAnswerMailInterface
{

    public function send(User $user, ForumSubject $forumSubject): void;
}