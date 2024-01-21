<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Entity\UserReview;
use App\Form\UserEditType;
use App\Form\UserReviewType;
use App\Service\CensorUserEmail\CensorUserEmailInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/utilisateur/mon-compte/{id}', name: 'app_user_dashboard_', requirements: ['id' => '\d+'])]
#[IsGranted('ROLE_USER')]
class UserDashboardController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    #[IsGranted('', subject: 'user')]
    public function index(User $user, CensorUserEmailInterface $censorUserEmail): Response
    {
        return $this->render('security/dashboard/index.html.twig', [
            'user' => $user,
            'censorUserEmail' => $censorUserEmail($user->getEmail()),
        ]);
    }

    #[Route('/edition', name: 'edit', methods: ['GET', 'POST'])]
    #[IsGranted('', subject: 'user')]
    public function edit(
        User $user,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $userPasswordEncoder
    ): Response {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (null !== $form->get('plainPassword')->getData()) {
                $user->setPassword($userPasswordEncoder->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                ));
            } elseif (null !== $form->get('email')->getData() && filter_var($form->get('email')->getData(), FILTER_VALIDATE_EMAIL)) {
                $user->setEmail($form->get('email')->getData());
            }
            $user->setUsername(str_replace(' ', '', $form->get('username')->getData()));
            $em->flush();
            $this->addFlash('success', 'Votre compte a bien été modifié.');

            return $this->redirectToRoute('app_user_dashboard_index', [
                'id' => $user->getId()]
            );
        }

        return $this->render('security/dashboard/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/mon-avis', name: 'review', methods: ['GET', 'POST'])]
    #[IsGranted('', subject: 'user')]
    public function userReview(
        User $user,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $review = new UserReview();
        $form = $this->createForm(UserReviewType::class, $review);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $review->setUser($user);
            $em->persist($review);
            $em->flush();
            $this->addFlash('success', 'Merci ! Votre avis a bien été envoyé.');

            return $this->redirectToRoute('app_user_dashboard_index', [
                'id' => $user->getId()]
            );
        }

        return $this->render('security/dashboard/user_review.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/mes-victoires', name: 'victory', methods: ['GET'])]
    public function victory(): Response
    {
        return $this->render('security/dashboard/victory.html.twig');
    }

    #[Route('/suppression-du-compte', name: 'delete', methods: ['GET', 'POST'])]
    #[IsGranted('', subject: 'user')]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        $this->container->get('security.token_storage')->setToken(null);
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'Votre compte a bien été supprimé.');

        return $this->redirectToRoute('app_home_index');
    }
}
