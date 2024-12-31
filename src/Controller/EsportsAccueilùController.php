<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EsportsAccueilùController extends AbstractController
{
    #[Route('/esports', name: 'app_esports_accueil_')]
    public function index(AuthenticationUtils $authenticationUtils,
    SessionInterface $session): Response
    {
        
    
        // Récupérer les erreurs de connexion, s'il y en a
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier nom d'utilisateur saisi
        $lastUsername = $authenticationUtils->getLastUsername();

        // Si l'utilisateur est authentifié
        if ($this->getUser()) {
            // Stocker l'ID utilisateur dans la session
            $session->set('user_id', $this->getUser()->getUserIdentifier());

        // Remplacez 'home' par votre route d'accueil
        }

        return $this->render('esports_all_views/index.html.twig', [
            'controller_name' => 'EsportsAccueilùController',
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
