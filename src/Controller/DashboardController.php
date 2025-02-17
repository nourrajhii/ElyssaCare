<?php

namespace App\Controller;

use App\Form\LaboratoireFormType;
use App\Entity\Laboratoire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;

use App\Entity\Analyse;
use App\Form\AnalyseType;
use App\Repository\AnalyseRepository;




class DashboardController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }
    #[Route('/back', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/back.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(Request $request): Response
    {
        $lab=new Laboratoire();
        $form =$this->createForm(LaboratoireFormType::class,$lab);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($lab);
            $this->em->flush();
        }
        


        
       # return $this->render('dashboard/about.html.twig', [
          #  'controller_name' => 'DashboardController',
       # ]);

        return $this->render('dashboard/about.html.twig', [
            'form' => $form->createView(),
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
