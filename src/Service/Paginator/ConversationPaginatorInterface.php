<?php

namespace App\Service\Paginator;

use App\Entity\User;
use App\Entity\UserMessage;

interface ConversationPaginatorInterface
{
    public function __invoke(UserMessage $userMessage, User $user, int $itemsPerPage): \Knp\Component\Pager\Pagination\PaginationInterface;
}
