<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserEditType;
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
    public function index(User $user, CensorUserEmailInterface $censorUserEmail): Response
    {
        return $this->render('security/dashboard/index.html.twig', [
            'user' => $user,
            'censorUserEmail' => $censorUserEmail($user->getEmail())
        ]);
    }

    #[Route('/edition', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        User $user,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $userPasswordEncoder
    ): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            if ($form->get('plainPassword')->getData() !== null) {
                $user->setPassword($userPasswordEncoder->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                ));
            }elseif ($form->get('email')->getData() !== null && filter_var($form->get('email')->getData(), FILTER_VALIDATE_EMAIL)){
                $user->setEmail($form->get('email')->getData());
            }
            $em->flush();
            $this->addFlash('success', 'Votre compte a bien été modifié.');
            return $this->redirectToRoute('app_user_dashboard_index', [
                'id' => $user->getId()]
            );
        }
        return $this->render('security/dashboard/edit.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }

    #[Route('/mes-victoires', name: 'victory', methods: ['GET'])]
    public function victory(): Response
    {
        return $this->render('security/dashboard/victory.html.twig');
    }

    #[Route('/suppression-du-compte', name: 'delete', methods: ['GET', 'POST'])]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        $this->addFlash('success', 'Votre compte a bien été supprimé.');
        return $this->redirectToRoute('app_home_index');
    }
}