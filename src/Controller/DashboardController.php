<?php

namespace App\Controller;
 
use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
 
class DashboardController extends AbstractController
{
    #[Route('/back', name: 'app_dashboard')]
      
     public function index(SessionInterface $session, UserRepository $userRepository): Response
    {
        $roleCounts = $userRepository->countUsersByRole();

        // Calculer le pourcentage
        $totalUsers = array_sum($roleCounts);
        $percentages = [];
    
        if ($totalUsers > 0) {
            foreach ($roleCounts as $role => $count) {
                $percentages[$role] = round(($count / $totalUsers) * 100, 2);
            }
        }
    
        return $this->render('dashboard/back.html.twig', [
            'percentages' => $percentages,
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
     #[Route('/evnts', name: 'app_events')]
    public function events(): Response
    {
        return $this->render('dashboard/events.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
    
 }
