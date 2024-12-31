<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(
            AuthenticationUtils $authenticationUtils,
            SessionInterface $session
        ): Response {
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

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);


    }


    #[Route('/check-user', name: 'check_user')]
    public function someAction(SessionInterface $session): Response
    {
        $userId = $session->get('user_id');

        if ($userId) {
            return $this->json(['message' => 'Utilisateur connecté', 'user_id' => $userId]);
        }

        return $this->json(['message' => 'Aucun utilisateur connecté']);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
