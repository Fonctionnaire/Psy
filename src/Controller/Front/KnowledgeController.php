<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tout-savoir', name: 'app_knowledge_')]
class KnowledgeController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('front/knowledge/index.html.twig');
    }

    #[Route('/trouble-anxieux', name: 'anxiety', methods: ['GET'])]
    public function anxiety(): Response
    {
        return $this->render('front/knowledge/anxiety.html.twig');
    }

    #[Route('/crise-angoisse', name: 'anxiety_attack', methods: ['GET'])]
    public function anxietyAttack(): Response
    {
        return $this->render('front/knowledge/anxietyAttack.html.twig');
    }

    #[Route('/attaque-de-panique', name: 'panic_attack', methods: ['GET'])]
    public function panicAttack(): Response
    {
        return $this->render('front/knowledge/panicAttack.html.twig');
    }

    #[Route('/trouble-panique', name: 'panic_disorder', methods: ['GET'])]
    public function panicDisorder(): Response
    {
        return $this->render('front/knowledge/panicDisorder.html.twig');
    }

    #[Route('/agoraphobie', name: 'agoraphobia', methods: ['GET'])]
    public function agoraphobia(): Response
    {
        return $this->render('front/knowledge/agoraphobia.html.twig');
    }

    #[Route('/biporalite', name: 'bipolar', methods: ['GET'])]
    public function bipolar(): Response
    {
        return $this->render('front/knowledge/bipolar.html.twig');
    }

    #[Route('/depression', name: 'depression', methods: ['GET'])]
    public function depression(): Response
    {
        return $this->render('front/knowledge/depression.html.twig');
    }

    #[Route('/burnout', name: 'burnout', methods: ['GET'])]
    public function burnout(): Response
    {
        return $this->render('front/knowledge/burnout.html.twig');
    }

    #[Route('/phobies', name: 'phobia', methods: ['GET'])]
    public function phobia(): Response
    {
        return $this->render('front/knowledge/phobia.html.twig');
    }

    #[Route('/solitude', name: 'loneliness', methods: ['GET'])]
    public function loneliness(): Response
    {
        return $this->render('front/knowledge/loneliness.html.twig');
    }
}
