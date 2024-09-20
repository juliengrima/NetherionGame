<?php

namespace App\Controller;

use App\Entity\Images;
use App\Form\ImagesType;
use App\Repository\ImagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/images')]
class ImagesController extends AbstractController
{
    #[Route('/', name: 'app_images_index', methods: ['GET'])]
    public function index(ImagesRepository $imagesRepository): Response
    {
        return $this->render('images/index.html.twig', [
            'images' => $imagesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_images_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $image = new Images();
        $form = $this->createForm(ImagesType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Gérer l'upload de fichier
            $uploadedFile = $form['link']->getData();
            if ($uploadedFile) {
                if ($image->isVideo()) {  // Assurez-vous que 'isVideo' est bien le getter pour le booléen 'video'
                    $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/videos';
                    $subdirectory = 'videos';
                } else {
                    $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/images';
                    $subdirectory = 'images';
                }
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'.'.$uploadedFile->guessExtension();

                try {
                    $uploadedFile->move(
                        $destination,
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer les erreurs si nécessaire
                }

                // Mettre à jour l'entité avec le chemin du fichier
                // $image->setLink('/uploads/images/'.$newFilename);
                $image->setLink('/uploads/' . $subdirectory . '/' . $newFilename);
            }
            $entityManager->persist($image);
            $entityManager->flush();

            return $this->redirectToRoute('app_images_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('images/new.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_images_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Images $image, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ImagesType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form['link']->getData();
            if ($uploadedFile) {
                // Handle file upload
                if ($image->isVideo()) {  // Assurez-vous que 'isVideo' est bien le getter pour le booléen 'video'
                    $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/videos';
                    $subdirectory = 'videos';
                } else {
                    $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/images';
                    $subdirectory = 'images';
                }
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'.'.$uploadedFile->guessExtension();

                try {
                    $uploadedFile->move(
                        $destination,
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exceptions if needed
                }

                // $image->setLink('/uploads/images/'.$newFilename);
                $image->setLink('/uploads/' . $subdirectory . '/' . $newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_images_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('images/edit.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_images_delete', methods: ['POST'])]
    public function delete(Request $request, Images $image, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->getPayload()->getString('_token'))) {
            
            // Récupérer le chemin du fichier à supprimer
            $fileLink = $image->getLink();  // Chemin du fichier stocké dans l'entité (ex: /uploads/images/...)
            $filePath = $this->getParameter('kernel.project_dir') . '/public' . $fileLink;

            // Vérifier si le fichier existe
            if (file_exists($filePath)) {
                // Supprimer le fichier
                unlink($filePath);
            }
            
            $entityManager->remove($image);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_images_index', [], Response::HTTP_SEE_OTHER);
    }
}
