<?php
<<<<<<< HEAD
namespace App\Controller;

use App\Entity\Events;
use App\Form\EventsType;
use App\Repository\EventsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
=======

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
>>>>>>> 8ba3048163ae6bc316db3eb80ca3688bc7ea3320
    public function about(): Response
    {
        return $this->render('dashboard/about.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
<<<<<<< HEAD

    #[Route('/events', name: 'app_events')]
    public function events(EventsRepository $eventsRepository): Response
    {
        return $this->render('dashboard/events.html.twig', [
            'events' => $eventsRepository->findAll(),
        ]);
    }
    #[Route('/sponsor', name: 'app_sponsor')]
    public function sponsor(EventsRepository $eventsRepository): Response
    {
        return $this->render('dashboard/sponsor.html.twig', [
            'sponsor' => $eventsRepository->findAll(),
        ]);
    }

   
}
=======
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
>>>>>>> 8ba3048163ae6bc316db3eb80ca3688bc7ea3320
