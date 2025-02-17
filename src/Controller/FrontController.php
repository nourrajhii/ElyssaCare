<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Laboratoire;
use Doctrine\ORM\EntityManagerInterface;
use TCPDF;
use App\Entity\Analyse;
use App\Form\AnalyseType;
use App\Repository\AnalyseRepository;

class FrontController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function index(): Response
    {
        return $this->render('front/front.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    #[Route('/frontlab', name: 'app_frontlab')]
    public function frontLab(EntityManagerInterface $entityManager): Response
    {
        // Récupérer les laboratoires depuis la base de données
        $laboratoires = $entityManager->getRepository(Laboratoire::class)->findAll();

        return $this->render('laboratoire/frontlab.html.twig', [
            'laboratoires' => $laboratoires, // 🔹 On passe bien la variable à Twig
        ]);
        
    }

    #[Route('/', name: 'app_frontindex', methods: ['GET'])]
public function frontindex(AnalyseRepository $analyseRepository): Response
{
    $analyses = $analyseRepository->findAll(); // Vérifie si cela retourne bien des résultats
    dump($analyses); // 🔹 Ajoute cette ligne pour voir les données dans le debug de Symfony
    return $this->render('analyse/frontindex.html.twig', [
        'analyses' => $analyses,
    ]);
}




    #[Route('/analyse/{id}/pdf', name: 'app_analyse_pdf')]
    public function generatePdf(Analyse $analyse): Response
    {
        // Création du PDF
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('ElyssaCare');
        $pdf->SetTitle('Détails de l\'Analyse');
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();

        // Contenu du PDF
        $html = $this->renderView('analyse/frontindex.html.twig', [
            'analyse' => $analyse
        ]);

        $pdf->writeHTML($html, true, false, true, false, '');

        // Retourne le PDF en réponse
        return new Response(
            $pdf->Output('analyse_'.$analyse->getId().'.pdf', 'I'),
            200,
            ['Content-Type' => 'application/pdf']
        );
    }

    
}
