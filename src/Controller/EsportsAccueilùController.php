<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EsportsAccueilùController extends AbstractController
{
    #[Route('/esports', name: 'app_esports_accueil_')]
    public function index(): Response
    {
        return $this->render('esports_all_views/index.html.twig', [
            'controller_name' => 'EsportsAccueilùController',
        ]);
    }
}
