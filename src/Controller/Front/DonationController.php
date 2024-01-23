<?php

namespace App\Controller\Front;

use App\Entity\Donation;
use App\Form\DonationType;
use App\Service\Email\DonationMail\DonationMailToAdminInterface;
use App\Service\Email\DonationMail\DonationMailToUserInterface;
use App\Service\Stripe\StripeDonationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/don', name: 'app_donation_')]
class DonationController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        StripeDonationInterface $stripeDonation
    ): Response
    {

        $donation = new Donation();
        $form = $this->createForm(DonationType::class, $donation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($donation);
            $this->em->flush();

            return $this->redirect($stripeDonation($form->getData())->url);
        }

        return $this->render('front/donation/index.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/success/{stripeSessionId}', name: 'payment_success', methods: ['GET'])]
    public function paymentSuccess(
        $stripeSessionId,
        DonationMailToUserInterface $donationMailToUser,
        DonationMailToAdminInterface $donationMailToAdmin
    ): Response
    {

        $donation = $this->em->getRepository(Donation::class)->findOneByStripeSessionId($stripeSessionId);

        if(!$donation)
        {
            return $this->redirectToRoute('app_home_index');
        }

        if(!$donation->getIsPaid())
        {
            $donation->setIsPaid(1);
            $this->em->flush();
        }

        $donationMailToAdmin($donation);
        $donationMailToUser($donation);

        return $this->render('front/donation/paymentSuccess.html.twig', [
            'donation' => $donation
        ]);
    }

    #[Route('/erreur/{stripeSessionId}', name: 'payment_error', methods: ['GET'])]
    public function paymentError($stripeSessionId): Response
    {
        $donation = $this->em->getRepository(Donation::class)->findOneByStripeSessionId($stripeSessionId);

        if(!$donation)
        {
            return $this->redirectToRoute('app_home_index');
        }

        return $this->render('front/donation/paymentError.html.twig', [
            'donation' => $donation,
        ]);
    }

}
