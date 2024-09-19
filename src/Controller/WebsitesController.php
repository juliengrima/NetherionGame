<?php

namespace App\Controller;

use App\Entity\Websites;
use App\Form\WebsitesType;
use App\Repository\WebsitesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/websites')]
class WebsitesController extends AbstractController
{
    #[Route('/', name: 'app_websites_index', methods: ['GET'])]
    public function index(WebsitesRepository $websitesRepository): Response
    {
        return $this->render('websites/index.html.twig', [
            'websites' => $websitesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_websites_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $website = new Websites();
        $form = $this->createForm(WebsitesType::class, $website);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($website);
            $entityManager->flush();

            return $this->redirectToRoute('app_websites_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('websites/new.html.twig', [
            'website' => $website,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_websites_show', methods: ['GET'])]
    public function show(Websites $website): Response
    {
        return $this->render('websites/show.html.twig', [
            'website' => $website,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_websites_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Websites $website, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WebsitesType::class, $website);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_websites_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('websites/edit.html.twig', [
            'website' => $website,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_websites_delete', methods: ['POST'])]
    public function delete(Request $request, Websites $website, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$website->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($website);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_websites_index', [], Response::HTTP_SEE_OTHER);
    }
}
