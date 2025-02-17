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
    #[Route('/blog', name: 'app_blog')]
    public function blog(): Response
    {
        return $this->render('dashboard/blog.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
    #[Route('/commentaire', name: 'app_commentaire')]
    public function commentaire(): Response
    {
        return $this->render('dashboard/commentaire.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
