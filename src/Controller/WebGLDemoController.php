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
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/webgldemo')]
class WebGLDemoController extends AbstractController
{
    #[Route('/', name: 'app_webgldemo_index', methods: ['GET'])]
    public function index(WebGLDemoRepository $webGLDemoRepository): Response
    {
        return $this->render('webgldemo/index.html.twig', [
            'webgldemos' => $webGLDemoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_webgldemo_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $webGLDemo = new WebGLDemo();
    $form = $this->createForm(WebGLDemoType::class, $webGLDemo);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gérer l'upload du fichier .data
        $dataFile = $form->get('dataFile')->getData();

        if ($dataFile) {
            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/upload/webgldatas';

            // Générer un nom basé sur la relation Games
            $gameName = $webGLDemo->getGame() ? $webGLDemo->getGame()->getName() : 'default';
            $fileName = $gameName . '_' . uniqid() . '.' . $dataFile->guessExtension();

            try {
                // Déplacer le fichier
                $dataFile->move($uploadDir, $fileName);
                $webGLDemo->setDataFilePath('/uploads/webgldatas/' . $fileName);
            } catch (FileException $e) {
                $this->addFlash('error', 'Erreur lors du téléchargement du fichier.');
            }
        }

        $entityManager->persist($webGLDemo);
        $entityManager->flush();

        return $this->redirectToRoute('app_webgldemo_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('webgldemo/new.html.twig', [
        'webgldemo' => $webGLDemo,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_webgldemo_show', methods: ['GET'])]
    public function show(WebGLDemo $webGLDemo): Response
    {
        return $this->render('webgldemo/show.html.twig', [
            'webgldemo' => $webGLDemo,
        ]);
    }

    #[Route('/{id}', name: 'app_webgldemo_delete', methods: ['POST'])]
    public function delete(Request $request, WebGLDemo $webGLDemo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$webGLDemo->getId(), $request->request->get('_token'))) {
            // Chemin du fichier data
            $dataFilePath = $this->getParameter('kernel.project_dir') . '/public/uploads/webgldatas/' . $webGLDemo->getLink();
            
            // Vérifier si le fichier existe
            if (file_exists($dataFilePath)) {
                // Supprimer le fichier
                unlink($dataFilePath);
            }
    
            // Supprimer l'entité de la base de données
            $entityManager->remove($webGLDemo);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_webgldemo_index', [], Response::HTTP_SEE_OTHER);
    }
}
