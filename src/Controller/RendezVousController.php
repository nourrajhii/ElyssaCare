<?php
namespace App\Controller;

use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Laboratoire;
use App\Repository\LaboratoireRepository;

#[Route('/rendez/vous')]
class RendezVousController extends AbstractController
{
    #[Route('/', name: 'app_rendez_vous_index', methods: ['GET'])]
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        return $this->render('rendez_vous/index.html.twig', [
            'rendez_vouses' => $rendezVousRepository->findAll(),
        ]);
    }

    #[Route('/new/{laboratoire}', name: 'app_rendez_vous_new', methods: ['GET', 'POST'])]
    public function new(Request $request, 
                        EntityManagerInterface $entityManager, 
                        LaboratoireRepository $laboratoireRepository, 
                        int $laboratoire): Response
    {
        // Récupérer le laboratoire sélectionné
        $lab = $laboratoireRepository->find($laboratoire);
        
        if (!$lab) {
            throw $this->createNotFoundException('Laboratoire non trouvé');
        }

        $rendezVou = new RendezVous();
        // Pré-remplir le laboratoire dans le rendez-vous
        $rendezVou->setLaboratoire($lab);

        // Créer et gérer le formulaire de rendez-vous
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rendezVou);
            $entityManager->flush();

            return $this->redirectToRoute('app_rendez_vous_show', ['id' => $rendezVou->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rendez_vous/new.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
            'laboratoire' => $lab, // Optionnel, pour afficher des informations supplémentaires sur le laboratoire
        ]);
    }

    #[Route('/{id}', name: 'app_rendez_vous_show', methods: ['GET'])]
    public function show(RendezVous $rendezVou): Response
    {
        return $this->render('rendez_vous/show.html.twig', [
            'rendez_vou' => $rendezVou,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rendez_vous_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RendezVous $rendezVou, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_frontlab', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rendez_vous/edit.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rendez_vous_delete', methods: ['POST'])]
    public function delete(Request $request, RendezVous $rendezVou, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rendezVou->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rendezVou);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/analyse', name: 'app_backanalyse', methods: ['GET'])]
    #[ParamConverter('rendezVou', class: 'App\Entity\RendezVous')]
    public function backanalyse(RendezVousRepository $rendezVousRepository): Response
    {
        return $this->render('analyse/backanalyse.html.twig', [
            'rendez_vouses' => $rendezVousRepository->findAll(),
        ]);
    }
}
