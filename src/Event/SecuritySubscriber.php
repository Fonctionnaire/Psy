<?php

namespace App\Event;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class SecuritySubscriber extends AbstractController implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
            LogoutEvent::class => 'onLogout',
        ];
    }

    public function onLoginSuccess(): void
    {
        $this->addFlash(
            'success',
            'Vous êtes désormais connecté.e.'
        );
    }

    public function onLogout(): void
    {
        $this->addFlash(
            'success',
            'Vous êtes désormais déconnecté.e.'
        );
    }
}