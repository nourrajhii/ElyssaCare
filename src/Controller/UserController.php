<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Dompdf\Dompdf;
use Dompdf\Options;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;





#[Route('/user')]
class UserController extends AbstractController
{
    
    private $passwordHasher;
   

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
    $this->passwordHasher = $passwordHasher;
  
    }

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $searchTerm = $request->query->get('search', '');
    $sortOrder = $request->query->get('sort', 'asc'); // 'asc' par défaut pour l'ordre croissant

    // Filtrer les utilisateurs en fonction du terme de recherche
    $users = $userRepository->findBySearchTermAndSort($searchTerm, $sortOrder);

    return $this->render('user/index.html.twig', [
        'users' => $users,
        'searchTerm' => $searchTerm, // passer le terme de recherche à la vue
        'sortOrder' => $sortOrder,   // passer l'ordre de tri à la vue
    ]);
    }
    

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SessionInterface $session, Recaptcha3Validator $recaptcha3Validator): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $score = $recaptcha3Validator->getLastResponse()->getScore();

            $plainpwd = $user->getPassword();
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainpwd);
            $user->setPassword($hashedPassword);

            $session->set('user_data', [
                'username' => $user->getname(),
                'email' => $user->getEmail(),
            ]);

            $entityManager->persist($user);
            $entityManager->flush();

            $session->set('user', $user);
            return $this->redirectToRoute('app_login', ['id' => $user->getId()]);
        }
       

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

   #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $userData = $session->get('user_edit_data', []);

        // Pré-remplir les champs si des données existent en session
        if (!empty($userData)) {
            $user->setname($userData['name'] ?? $user->getname());
            $user->setEmail($userData['email'] ?? $user->getEmail());
        }

        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           
            $plainpwd = $user->getPassword();
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainpwd);
            $user->setPassword($hashedPassword);
            
            $session->set('user_edit_data', [
                'name' => $user->getname(),
                'email' => $user->getEmail(),
            ]);

            $entityManager->flush();

            // Supprimer les données de session après la modification
            $session->remove('user_edit_data');

            
            
            return $this->redirectToRoute('app_user_profile', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);

        }

        $templateParent = in_array('ROLE_ADMIN', $user->getRoles()) ? 'base_back.html.twig' : 'front/front.html.twig';
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'templateParent' => $templateParent,
        ]);
    }

   #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager,  SessionInterface $session, Security $security): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {

            
            $entityManager->remove($user);
            $entityManager->flush();
            $session->invalidate();
            return $this->redirectToRoute('app_login');
        }

       
        return $this->redirectToRoute('app_login', ['id' => $user->getId()]);
    }

    #[Route('/profile/{id}', name: 'app_user_profile')]
    public function profile(User $user): Response
    {
        
        // Si $user est null, rediriger vers la page de connexion
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $templateParent = in_array('ROLE_ADMIN', $user->getRoles()) ? 'base_back.html.twig' : 'front/front.html.twig';
    
        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'templateParent' => $templateParent,
        ]);
    }
    #[Route('/{id}/change-password', name: 'app_user_change_password', methods: ['GET', 'POST'])]
public function changePassword(Request $request, User $user, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
{
    if ($request->isMethod('POST')) {
        $oldPassword = $request->request->get('old_password');
        $newPassword = $request->request->get('new_password');
        $confirmNewPassword = $request->request->get('confirm_new_password');

        // Vérifier si l'ancien mot de passe est correct
        if (!$passwordHasher->isPasswordValid($user, $oldPassword)) {
            $this->addFlash('error', 'Ancien mot de passe incorrect.');
        } elseif ($newPassword !== $confirmNewPassword) {
            $this->addFlash('error', 'Les nouveaux mots de passe ne correspondent pas.');
        } elseif (strlen($newPassword) < 8) {
            $this->addFlash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
        } elseif (!preg_match('/[A-Z]/', $newPassword) || !preg_match('/[0-9]/', $newPassword)) {
            $this->addFlash('error', 'Le mot de passe doit contenir au moins une majuscule et un chiffre.');
        } else {
            // Hacher et mettre à jour le nouveau mot de passe
            $hashedNewPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedNewPassword);
            $entityManager->flush();

            // Redirection avec un message de succès
            $this->addFlash('success', 'Mot de passe mis à jour avec succès.');
            return $this->redirectToRoute('app_user_profile', ['id' => $user->getId()]);
        }
    }

    $templateParent = in_array('ROLE_ADMIN', $user->getRoles()) ? 'base_back.html.twig' : 'front/front.html.twig';
    return $this->render('user/change_password.html.twig', [
        'user' => $user,
        'templateParent' => $templateParent,
    ]);
}


#[Route('/{id}/export-pdf', name: 'app_user_export_pdf')]
public function exportPdf(User $user): Response
{
    // Créer l'instance de Dompdf
    $dompdf = new Dompdf();
    
    // Charger le contenu HTML de la page de profil spécifique pour le PDF
    $html = $this->renderView('user/pdf.html.twig', [
        'user' => $user,
    ]);

    // Charger l'HTML dans Dompdf
    $dompdf->loadHtml($html);

    // Définir les options (par exemple la taille de la page)
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    $dompdf->setOptions($options);

    // Rendre le PDF
    $dompdf->render();

    // Retourner le PDF en réponse avec les entêtes nécessaires
    return new Response(
        $dompdf->output(),
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="profile.pdf"',
        ]
    );
}



}
