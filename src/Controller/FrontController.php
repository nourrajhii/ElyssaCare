<?php
<<<<<<< HEAD

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Medicaments;
use App\Entity\MaterielMedical;
use App\Form\MaterielMedicalType;
use App\Repository\MedicamentsRepository;
use App\Repository\MaterielMedicalRepository;
=======
<<<<<<< HEAD

namespace App\Controller;

use App\Entity\Events;
use App\Form\EventsType;
use App\Repository\EventsRepository;
use App\Repository\SponsorRepository; // Assurez-vous d'importer le bon repository
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
=======
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Blog; // Ajoutez l'entité Blog
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commentaire;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
>>>>>>> 8ba3048163ae6bc316db3eb80ca3688bc7ea3320
>>>>>>> 1d8c7f2fa47416633189c82d9f37c17437aae32e

class FrontController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function index(): Response
    {
        return $this->render('front/front.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

<<<<<<< HEAD
    #[Route('/frontmedica', name: 'app_front_medica')]
    public function medicaments(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les médicaments
        $medicaments = $entityManager->getRepository(Medicaments::class)->findAll();

        return $this->render('medicaments/frontmedica.html.twig', [
            'medicaments' => $medicaments,
        ]);
    }

    #[Route('/showmedicament/{id}', name: 'app_show_medica', requirements: ['id' => '\d+'])]
    public function showMedicament(int $id, MedicamentsRepository $medicamentsRepository): Response
    {
        // Récupérer un seul médicament par son ID
        $medicament = $medicamentsRepository->find($id);

        // Vérifier si le médicament existe
        if (!$medicament) {
            throw $this->createNotFoundException("Médicament introuvable.");
        }

        return $this->render('medicaments/showfront.html.twig', [
            'medicament' => $medicament,
        ]);
    }

    #[Route('/frontmate', name: 'app_front_mate')]
    public function materiel(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les matériels médicaux
        $materiels = $entityManager->getRepository(MaterielMedical::class)->findAll();

        return $this->render('materiel_medical/frontmate.html.twig', [
            'materiels' => $materiels,
        ]);
    }

    #[Route('/showmateriel/{id}', name: 'app_show_front', requirements: ['id' => '\d+'])]
    public function showMateriel(int $id, MaterielMedicalRepository $materielMedicalRepository): Response
    {
        // Récupérer le matériel médical par son ID
        $materiel = $materielMedicalRepository->find($id);

        // Vérifier si le matériel existe
        if (!$materiel) {
            throw $this->createNotFoundException("Matériel médical introuvable.");
        }

        return $this->render('materiel_medical/showfront.html.twig', [
            'materiel_medical' => $materiel,
        ]);
    }
}
=======
<<<<<<< HEAD
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
=======
    #[Route('/frontblog', name: 'app_front_blog')]
    public function blog(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les blogs
        $blogs = $entityManager->getRepository(Blog::class)->findAll();

        // Passer la variable 'blogs' à la vue
        return $this->render('blog/frontblog.html.twig', [
            'blogs' => $blogs, // Passez les blogs ici
        ]);
    }

    // Exemple pour afficher un blog spécifique
    #[Route('/frontblog/{id}', name: 'app_front_show_blog')]
    public function show(int $id, BlogRepository $blogRepository): Response
    {
        // Récupérer un blog spécifique avec l'ID
        $blog = $blogRepository->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Le blog n\'a pas été trouvé');
        }

        return $this->render('blog/frontshowblog.html.twig', [
            'blog' => $blog, // Passez l'objet blog ici
        ]);
    }


    #[Route('/frontcoment', name: 'app_front_coment')]
    public function coment(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les blogs
        $commentaires = $entityManager->getRepository(commentaire::class)->findAll();

        // Passer la variable 'blogs' à la vue
        return $this->render('commentaire/frontcoment.html.twig', [
            'commentaires' => $commentaires, // Passez les blogs ici
        ]);
    }



    #[Route('/{id}/edit', name: 'app_front_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_front_coment', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire/frontedit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_front_show', methods: ['GET'])]
    public function frontshow(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/frontshow.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }



    
}
>>>>>>> 8ba3048163ae6bc316db3eb80ca3688bc7ea3320
>>>>>>> 1d8c7f2fa47416633189c82d9f37c17437aae32e
