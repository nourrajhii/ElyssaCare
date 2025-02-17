<?php

namespace App\Controller;

use App\Entity\Medicaments;
use App\Form\MedicamentsType;
use App\Repository\MedicamentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/medicaments')]
final class MedicamentsController extends AbstractController
{
    #[Route(name: 'app_medicaments_index', methods: ['GET'])]
    public function index(MedicamentsRepository $medicamentsRepository): Response
    {
        return $this->render('medicaments/index.html.twig', [
            'medicaments' => $medicamentsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_medicaments_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $medicament = new Medicaments();
        $form = $this->createForm(MedicamentsType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('medicament_images_directory'),
                        $newFilename
                    );
                    $medicament->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Impossible d\'uploader l\'image.');
                }
            }

            $entityManager->persist($medicament);
            $entityManager->flush();

            return $this->redirectToRoute('app_medicaments_index');
        }

        return $this->render('medicaments/new.html.twig', [
            'medicament' => $medicament,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_medicaments_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Medicaments $medicament, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(MedicamentsType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('medicament_images_directory'),
                        $newFilename
                    );
                    $medicament->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Impossible d\'uploader l\'image.');
                }
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_medicaments_index');
        }

        return $this->render('medicaments/edit.html.twig', [
            'medicament' => $medicament,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_medicaments_show', methods: ['GET'])]
    public function show(Medicaments $medicament): Response
    {
        return $this->render('medicaments/show.html.twig', [
            'medicament' => $medicament,
        ]);
    }

    #[Route('/{id}', name: 'app_medicaments_delete', methods: ['POST'])]
    public function delete(Request $request, Medicaments $medicament, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $medicament->getId(), $request->request->get('_token'))) {
            $entityManager->remove($medicament);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_medicaments_index');
    }
}