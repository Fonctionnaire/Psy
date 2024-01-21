<?php

namespace App\Service\Paginator;

interface ForumPaginatorInterface
{
    public function __invoke(array $list, int $itemsPerPage): \Knp\Component\Pager\Pagination\PaginationInterface;
}
