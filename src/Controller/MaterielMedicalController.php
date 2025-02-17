<?php

namespace App\Controller;

use App\Entity\MaterielMedical;
use App\Form\MaterielMedicalType;
use App\Repository\MaterielMedicalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/materiel/medical')]
final class MaterielMedicalController extends AbstractController
{
    #[Route(name: 'app_materiel_medical_index', methods: ['GET'])]
    public function index(MaterielMedicalRepository $materielMedicalRepository): Response
    {
        return $this->render('materiel_medical/index.html.twig', [
            'materiel_medicals' => $materielMedicalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_materiel_medical_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $materielMedical = new MaterielMedical();
        $form = $this->createForm(MaterielMedicalType::class, $materielMedical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('materiel_images_directory'), // Récupère le dossier configuré
                        $newFilename
                    );
                    $materielMedical->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'upload de l\'image.');
                }
            }

            $entityManager->persist($materielMedical);
            $entityManager->flush();

            return $this->redirectToRoute('app_materiel_medical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('materiel_medical/new.html.twig', [
            'materiel_medical' => $materielMedical,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_materiel_medical_show', methods: ['GET'])]
    public function show(MaterielMedical $materielMedical): Response
    {
        return $this->render('materiel_medical/show.html.twig', [
            'materiel_medical' => $materielMedical,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_materiel_medical_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MaterielMedical $materielMedical, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MaterielMedicalType::class, $materielMedical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload d'une nouvelle image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('materiel_images_directory'),
                        $newFilename
                    );
                    $materielMedical->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'upload de l\'image.');
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_materiel_medical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('materiel_medical/edit.html.twig', [
            'materiel_medical' => $materielMedical,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_materiel_medical_delete', methods: ['POST'])]
    public function delete(Request $request, MaterielMedical $materielMedical, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materielMedical->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($materielMedical);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_materiel_medical_index', [], Response::HTTP_SEE_OTHER);
    }
}