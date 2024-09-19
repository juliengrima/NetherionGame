<?php

namespace App\Controller;

use App\Entity\NewsLetters;
use App\Entity\Websites;
use App\Repository\GamesRepository;
use App\Repository\WebsitesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request, GamesRepository $gamesRepository, WebsitesRepository $websitesRepository): Response
    {
        $games = $gamesRepository->findAll();
        $websites = $websitesRepository->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'games' => $games,
            'websites' => $websites,
        ]);
    }

    #[Route('/conditions', name: 'app_conditions')]
    public function conditions(Request $request, GamesRepository $gamesRepository,  WebsitesRepository $websitesRepository): Response
    {
        $games = $gamesRepository->findAll();
        $websites = $websitesRepository->findAll();
        return $this->render('home/conditions.html.twig', [
            'controller_name' => 'HomeController',
            'games' => $games,
            'websites' => $websites,
        ]);
    }

    #[Route('/confidentialities', name: 'app_confidentialities')]
    public function confidentialities(Request $request, GamesRepository $gamesRepository, WebsitesRepository $websitesRepository): Response
    {
        $games = $gamesRepository->findAll();
        $websites = $websitesRepository->findAll();
        return $this->render('home/confidentialities.html.twig', [
            'controller_name' => 'HomeController',
            'games' => $games,
            'websites' => $websites,
        ]);
    }
}
