<?php

namespace App\Controller\Security;

use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Service\Email\ResetPasswordMailInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class ForgotPasswordController extends AbstractController
{
    #[Route('/mot-de-passe-oublie', name: 'app_forgot_password', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        ResetPasswordMailInterface $resetPasswordMail
    ): Response {
        if ('POST' === $request->getMethod()) {
            $email = $request->request->get('forgot_password_email');
            $user = $userRepository->findOneBy(['email' => $email]);
            if ($user) {
                $user->setResetPasswordToken(Uuid::v4());
                $em->flush();
                $resetPasswordMail($user);
            }
            $this->addFlash('success', 'Un email vous a été envoyé pour réinitialiser votre mot de passe. Vérifiez vos spams.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/forgotPassword/index.html.twig');
    }

    #[Route('/nouveau-mot-de-passe/create/{resetPasswordToken}/{id<\d+>}', name: 'app_forgot_password_reset', methods: ['GET', 'POST'])]
    public function resetPassword(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        $resetPasswordToken,
        $id,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $userRepository->findOneBy(['resetPasswordToken' => $resetPasswordToken, 'id' => $id]);
        if (!$user) {
            $this->addFlash('danger', 'Le lien de réinitialisation du mot de passe n\'est pas valide.');

            return $this->redirectToRoute('app_forgot_password');
        }
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setResetPasswordToken(null);
            $user->setPassword(
                $passwordHasher->hashPassword($user, $form->get('password')->getData())
            );
            $em->flush();
            $this->addFlash('success', 'Votre nouveau mot de passe a bien été enregistré.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/forgotPassword/resetForm.html.twig', [
            'form' => $form,
        ]);
    }
}
