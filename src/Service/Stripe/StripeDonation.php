<?php

namespace App\Service\Stripe;

use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\RequestStack;

class StripeDonation implements StripeDonationInterface
{
    public function __construct(private EntityManagerInterface $em, private RequestStack $request, public string $stripeKey)
    {
    }

    public function __invoke($donation): Session
    {
        $stripeInfos = [];
        $YOUR_DOMAIN = $this->request->getCurrentRequest()->getSchemeAndHttpHost().'/';

        $stripeInfos[] = [
            'quantity' => 1,
            'price_data' => [
                'unit_amount' => $donation->getPrice(),
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'Don',
                ],
            ],
        ];

        Stripe::setApiKey($this->stripeKey);

        $checkout_session = Session::create([
            'line_items' => [
                $stripeInfos,
            ],
            'payment_method_types' => [
                'card',
            ],
            'customer_email' => $donation->getEmail(),
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN.'don/success/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN.'don/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $donation->setStripeSessionId($checkout_session->id);
        $this->em->flush();

        return $checkout_session;
    }
}
