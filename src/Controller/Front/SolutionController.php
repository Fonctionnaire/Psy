<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/solutions', name: 'app_solution_')]
class SolutionController extends AbstractController
{
    #[Route('/comprendre', name: 'understand', methods: ['GET'])]
    public function understand(): Response
    {
        return $this->render('front/solution/understand.html.twig');
    }

    #[Route('/medecin-traitant', name: 'doctor', methods: ['GET'])]
    public function doctor(): Response
    {
        return $this->render('front/solution/doctor.html.twig');
    }

    #[Route('/le-psy', name: 'psy', methods: ['GET'])]
    public function psy(): Response
    {
        return $this->render('front/solution/psy.html.twig');
    }

    #[Route('/conseils', name: 'advice', methods: ['GET'])]
    public function advice(): Response
    {
        return $this->render('front/solution/advice.html.twig');
    }

    #[Route('/exercices', name: 'exercice', methods: ['GET'])]
    public function exercice(): Response
    {
        return $this->render('front/solution/exercice.html.twig');
    }

    #[Route('/les-proches', name: 'relative', methods: ['GET'])]
    public function relative(): Response
    {
        return $this->render('front/solution/relative.html.twig');
    }
}
