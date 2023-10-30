<?php

namespace App\Service\Home;

use App\Repository\UserRepository;
use App\Repository\UserReviewRepository;

class HomeCountService implements HomeCountServiceInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserReviewRepository $userReviewRepository
    ) {
    }

    public function getHomeCount(): array
    {
        $nbUsers = $this->userRepository->count(['isAccountValidated' => true]);
        $nbVolunteers = $this->userRepository->count(['isAccountValidated' => true, 'isVolunteer' => true]);
        $nbUserReviews = $this->userReviewRepository->count(['isValidated' => true]);
        $rates = $this->userReviewRepository->findByIsValidated();

        $total = 0;
        foreach ($rates as $rate) {
            $total += $rate->getRate();
        }

        if (0 === $nbUserReviews) {
            $averageRate = 0;
        } else {
            $averageRate = ceil($total / $nbUserReviews);
        }

        $datas = [
            'nbUsers' => $nbUsers,
            'nbVolunteers' => $nbVolunteers,
            'nbUserReviews' => $nbUserReviews,
            'averageRate' => $averageRate,
        ];

        return $datas;
    }
}
