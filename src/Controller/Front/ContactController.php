<?php

namespace App\Controller\Front;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Service\Email\ContactMailInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/nous-contacter', name: 'app_contact_')]
class ContactController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        ContactRepository $contactRepository,
        ContactMailInterface $contactMail
    ): Response {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact)
            ->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $antiSpam = $form->get('w_m_c_a')->getData();
            if (null === $antiSpam) {
                $contact->setIsTermsAccepted($form->get('isTermsAccepted')->getData());
                $contactRepository->save($contact, true);
                $contactMail->sendToUser($contact);
                $contactMail->sendToAdmin($contact);
            }
            $this->addFlash('success', 'Votre message a bien été envoyé. Nous vous répondrons le plus rapidement possible.');

            return $this->redirectToRoute('app_contact_index');
        }

        return $this->render('front/contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
