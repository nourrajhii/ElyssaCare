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
use Dompdf\Dompdf;
use Dompdf\Options;

use Twig\Environment;
use Knp\Snappy\Pdf;
use App\Service\MedicalChatbotService;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;


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

#[Route('/chatbot', name: 'app_chatbot')]
    public function chatbot(Request $request, MedicalChatbotService $chatbotService): Response
    {
        $question = $request->request->get('question', '');
        $responseText = '';

        if ($question) {
            $responseText = $chatbotService->getResponse($question);
        }

        return $this->render('analyse/chatbot.html.twig', [
            'question' => $question,
            'response' => $responseText,
        ]);
    }
   

}

    

