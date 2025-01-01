<?php
// src/Controller/DashboardController.php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\Tournoi;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/esports', name: 'app_esports_accueil_')]
class DashboardController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        // Fetch data for the dashboard
        $commandes = $this->entityManager->getRepository(Commande::class)->findAll();
        $produits = $this->entityManager->getRepository(Produit::class)->findAll();
        $tournois = $this->entityManager->getRepository(Tournoi::class)->findAll();
        $users = $this->entityManager->getRepository(User::class)->findAll();

        // Prepare data for the chart (example: orders per day)
        $ordersPerDay = $this->getOrdersPerDay($commandes);

        return $this->render('dashboard/index.html.twig', [
            'commandes' => $commandes,
            'produits' => $produits,
            'tournois' => $tournois,
            'users' => $users,
            'ordersPerDay' => json_encode($ordersPerDay), // Convert to JSON for Chart.js
        ]);
    }

    private function getOrdersPerDay(array $commandes): array
    {
        $ordersPerDay = [];
        foreach ($commandes as $commande) {
            $date = $commande->getDateDeCommande()->format('Y-m-d');
            if (!isset($ordersPerDay[$date])) {
                $ordersPerDay[$date] = 0;
            }
            $ordersPerDay[$date]++;
        }
        return $ordersPerDay;
    }
}