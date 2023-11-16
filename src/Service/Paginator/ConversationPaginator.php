<?php

namespace App\Service\Paginator;

use App\Entity\User;
use App\Entity\UserMessage;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ConversationPaginator implements ConversationPaginatorInterface
{
    public function __construct(private PaginatorInterface $paginator, private RequestStack $request)
    {
    }

    public function __invoke(UserMessage $userMessage, User $user, int $itemsPerPage): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $userMessages = count($user->getUserConversation()->getUserMessages()) / $itemsPerPage;

        if (0 === $userMessages) {
            $userMessages = 1;
        }

        $subjects = $this->paginator->paginate(
            $user->getUserConversation()->getUserMessages(),
            $this->request->getCurrentRequest()->query->getInt('page', ceil($userMessages)),
            $itemsPerPage
        );

        $requestPage = (int) $this->request->getCurrentRequest()->get('page');

        if ($requestPage > ceil($subjects->getTotalItemCount() / $itemsPerPage)) {
            return $this->paginator->paginate(
                $user->getUserConversation()->getUserMessages(),
                1,
                $itemsPerPage
            );
        }

        return $subjects;
    }
}
