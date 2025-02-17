<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Medicaments;
use App\Entity\MaterielMedical;
use App\Form\MaterielMedicalType;
use App\Repository\MedicamentsRepository;
use App\Repository\MaterielMedicalRepository;

class FrontController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function index(): Response
    {
        return $this->render('front/front.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    #[Route('/frontmedica', name: 'app_front_medica')]
    public function medicaments(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les médicaments
        $medicaments = $entityManager->getRepository(Medicaments::class)->findAll();

        return $this->render('medicaments/frontmedica.html.twig', [
            'medicaments' => $medicaments,
        ]);
    }

    #[Route('/showmedicament/{id}', name: 'app_show_medica', requirements: ['id' => '\d+'])]
    public function showMedicament(int $id, MedicamentsRepository $medicamentsRepository): Response
    {
        // Récupérer un seul médicament par son ID
        $medicament = $medicamentsRepository->find($id);

        // Vérifier si le médicament existe
        if (!$medicament) {
            throw $this->createNotFoundException("Médicament introuvable.");
        }

        return $this->render('medicaments/showfront.html.twig', [
            'medicament' => $medicament,
        ]);
    }

    #[Route('/frontmate', name: 'app_front_mate')]
    public function materiel(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les matériels médicaux
        $materiels = $entityManager->getRepository(MaterielMedical::class)->findAll();

        return $this->render('materiel_medical/frontmate.html.twig', [
            'materiels' => $materiels,
        ]);
    }

    #[Route('/showmateriel/{id}', name: 'app_show_front', requirements: ['id' => '\d+'])]
    public function showMateriel(int $id, MaterielMedicalRepository $materielMedicalRepository): Response
    {
        // Récupérer le matériel médical par son ID
        $materiel = $materielMedicalRepository->find($id);

        // Vérifier si le matériel existe
        if (!$materiel) {
            throw $this->createNotFoundException("Matériel médical introuvable.");
        }

        return $this->render('materiel_medical/showfront.html.twig', [
            'materiel_medical' => $materiel,
        ]);
    }
}
