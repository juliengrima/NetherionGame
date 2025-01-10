<?php

namespace App\Controller;

use App\Entity\WebGLDemo;
use App\Form\WebGLDemoType;
use App\Repository\WebGLDemoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/web/g/l/demo')]
class WebGLDemoController extends AbstractController
{
    #[Route('/', name: 'app_web_g_l_demo_index', methods: ['GET'])]
    public function index(WebGLDemoRepository $webGLDemoRepository): Response
    {
        return $this->render('web_gl_demo/index.html.twig', [
            'web_g_l_demos' => $webGLDemoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_web_g_l_demo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $webGLDemo = new WebGLDemo();
        $form = $this->createForm(WebGLDemoType::class, $webGLDemo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($webGLDemo);
            $entityManager->flush();

            return $this->redirectToRoute('app_web_g_l_demo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('web_gl_demo/new.html.twig', [
            'web_g_l_demo' => $webGLDemo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_web_g_l_demo_show', methods: ['GET'])]
    public function show(WebGLDemo $webGLDemo): Response
    {
        return $this->render('web_gl_demo/show.html.twig', [
            'web_g_l_demo' => $webGLDemo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_web_g_l_demo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WebGLDemo $webGLDemo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WebGLDemoType::class, $webGLDemo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_web_g_l_demo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('web_gl_demo/edit.html.twig', [
            'web_g_l_demo' => $webGLDemo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_web_g_l_demo_delete', methods: ['POST'])]
    public function delete(Request $request, WebGLDemo $webGLDemo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$webGLDemo->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($webGLDemo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_web_g_l_demo_index', [], Response::HTTP_SEE_OTHER);
    }
}
