<?php

namespace App\Controller;

use App\Entity\Events;
use App\Form\EventsType;
use App\Repository\EventsRepository;
use App\Repository\SponsorRepository; // Assurez-vous d'importer le bon repository
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function index(): Response
    {
        return $this->render('front/front.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    #[Route('/event/{id}', name: 'app_eventes_show')]
public function showEvent(EventsRepository $eventsRepository, $id): Response
{
    $event = $eventsRepository->find($id);

    if (!$event) {
        throw $this->createNotFoundException('Événement non trouvé');
    }

    return $this->render('events/showF.html.twig', [
        'event' => $event,
    ]);
}
    
    #[Route('/eventes', name: 'app_eventes')]
    public function events(EventsRepository $eventsRepository): Response
    {
        return $this->render('front/eventsF.html.twig', [
            'events' => $eventsRepository->findAll(),
        ]);
    }

    #[Route('/sponsore', name: 'app_sponsore')]
    public function sponsor(SponsorRepository $sponsorRepository): Response // Injecter SponsorRepository ici
    {
        return $this->render('front/sponsorF.html.twig', [
            'sponsors' => $sponsorRepository->findAll(), // Récupérer les sponsors ici
        ]);
    }
   
}