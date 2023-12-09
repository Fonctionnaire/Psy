<?php

namespace App\Service\Paginator;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ForumPaginator implements ForumPaginatorInterface
{
    public function __construct(private PaginatorInterface $paginator, private RequestStack $request)
    {
    }

    public function __invoke(array $list, int $itemsPerPage): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $messages = count($list) / $itemsPerPage;

        if (0 === $messages) {
            $messages = 1;
        }

        $subjects = $this->paginator->paginate(
            $list,
            $this->request->getCurrentRequest()->query->getInt('page', 1),
            $itemsPerPage
        );

        $requestPage = (int) $this->request->getCurrentRequest()->get('page');

        if ($requestPage > ceil($subjects->getTotalItemCount() / $itemsPerPage)) {
            return $this->paginator->paginate(
                $list,
                1,
                $itemsPerPage
            );
        }

        return $subjects;
    }
}
