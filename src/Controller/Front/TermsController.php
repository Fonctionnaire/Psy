<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TermsController extends AbstractController
{
    #[Route('/mentions-legales', name: 'app_terms_legals', methods: ['GET'])]
    public function legal(): Response
    {
        return $this->render('front/terms/legal.html.twig');
    }

    #[Route('/conditions-d-utilisation', name: 'app_terms_usage', methods: ['GET'])]
    public function usageTerms(): Response
    {
        return $this->render('front/terms/terms_usage.html.twig');
    }
}
