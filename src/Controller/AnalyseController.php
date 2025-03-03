<?php

namespace App\Controller;

use App\Entity\Analyse;
use App\Form\AnalyseType;
use App\Repository\AnalyseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Knp\Snappy\Pdf;
use Twig\Environment;

use App\Service\MedicalChatbotService;






#[Route('/analyse')]
class AnalyseController extends AbstractController
{
    #[Route('/', name: 'app_analyse_index', methods: ['GET'])]
    public function index(AnalyseRepository $analyseRepository): Response
    {
        return $this->render('analyse/index.html.twig', [
            'analyses' => $analyseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_analyse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $analyse = new Analyse();
        $form = $this->createForm(AnalyseType::class, $analyse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($analyse);
            $entityManager->flush();

            return $this->redirectToRoute('app_analyse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('analyse/new.html.twig', [
            'analyse' => $analyse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_analyse_show', methods: ['GET'])]
    public function show(Analyse $analyse): Response
    {
        return $this->render('analyse/show.html.twig', [
            'analyse' => $analyse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_analyse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Analyse $analyse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnalyseType::class, $analyse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_analyse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('analyse/edit.html.twig', [
            'analyse' => $analyse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_analyse_delete', methods: ['POST'])]
    public function delete(Request $request, Analyse $analyse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$analyse->getId(), $request->request->get('_token'))) {
            $entityManager->remove($analyse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_analyse_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/pdf/{id}', name: 'app_analyse_pdf')]
    public function generatePdf(Analyse $analyse, Pdf $pdf, Environment $twig): Response
    {
        // Générer le contenu HTML du PDF à partir d'un template Twig
        $html = $twig->render('analyse/pdf.html.twig', [
            'analyse' => $analyse
        ]);
    
        // Générer le PDF
        $pdfContent = $pdf->getOutputFromHtml($html);
    
        // Retourner le PDF en tant que réponse HTTP pour le téléchargement
        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="analyse_'.$analyse->getId().'.pdf"' // Ceci permet de télécharger le PDF
        ]);
    }




   



    
    
}
