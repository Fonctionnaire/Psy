<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Entity\UserConversation;
use App\Form\RegisterType;
use App\Repository\UserConversationRepository;
use App\Repository\UserRepository;
use App\Service\Email\VerifyAccountMailInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $hasher,
        VerifyAccountMailInterface $verifyAccountMail,
        UserConversationRepository $userConversationRepository
    ): Response {
        if ($this->getUser()) {
            $this->addFlash('warning', 'Vous êtes déjà connecté.e.');

            return $this->redirectToRoute('app_home_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUsername(str_replace(' ', '', $form->get('username')->getData()));
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($hasher->hashPassword($user, $form->get('password')->getData()));
            $userRepository->save($user, true);

            $userConversation = new UserConversation();
            $userConversation->setUser($user);
            $userConversationRepository->save($userConversation, true);

            $verifyAccountMail($user);
            $this->addFlash('success', 'Votre compte a bien été créé. Vous allez recevoir un email afin de confirmer votre inscription.');

            return $this->redirectToRoute('app_home_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('security/register/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/inscription/confirmation/{registrationToken}/{id<\d+>}', name: 'app_register_confirmation', methods: ['GET', 'POST'])]
    public function verifyRegistration(
        Security $security,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        $registrationToken,
        $id
    ): Response {
        $user = $userRepository->findOneBy(['registrationToken' => $registrationToken, 'id' => $id]);
        if ($user) {
            $user->setIsAccountValidated(true);
            $user->setRegistrationToken(null);
            $em->flush();
            $this->addFlash('success', 'Votre compte a bien été validé. Vous pouvez maintenant vous connecter.');
        } else {
            $this->addFlash('warning', 'Votre compte n\'a pas pu être validé. Veuillez réessayer.');
        }

        return $this->redirectToRoute('app_login',
            [],
            Response::HTTP_SEE_OTHER
        );
    }
}
