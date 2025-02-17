<?php
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
    public function about(): Response
    {
        return $this->render('dashboard/about.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

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