<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/back', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/back.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('dashboard/about.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
    #[Route('/medicament', name: 'app_medicament')]
    public function medicament(): Response
    {
        return $this->render('dashboard/medicament.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
    #[Route('/materiel_medical', name: 'app_materiel_medical')]
    public function materiel_medical(): Response
    {
        return $this->render('dashboard/materiel_medical.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
