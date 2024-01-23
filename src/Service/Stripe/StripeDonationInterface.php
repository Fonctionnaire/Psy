<?php

namespace App\Service\Stripe;

interface StripeDonationInterface
{
    public function __invoke($donation): \Stripe\Checkout\Session;
}