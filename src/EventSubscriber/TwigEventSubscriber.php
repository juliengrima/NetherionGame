<?php
// src/EventSubscriber/TwigEventSubscriber.php

namespace App\EventSubscriber;

use App\Repository\GamesRepository;
use App\Repository\WebsitesRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $gamesRepository;
    private $websitesRepository;

    public function __construct(Environment $twig, 
                                GamesRepository $gamesRepository, 
                                WebsitesRepository $websitesRepository)
    {
        $this->twig = $twig;
        $this->gamesRepository = $gamesRepository;
        $this->websitesRepository = $websitesRepository;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $games = $this->gamesRepository->findAll();
        $websites = $this->websitesRepository->findAll();
        $this->twig->addGlobal('games', $games);
        $this->twig->addGlobal('websites', $websites);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}