<?php

namespace App\Controller;

use App\Entity\Group;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tournaments')]
final class TournamentsController extends AbstractController
{
  
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('', name: 'tournaments')]
    public function tournaments(): Response
    {
        return $this->render('esports_all_views/tournaments/tournaments.html.twig', [
            'controller_name' => 'TournoiController',
        ]);
    }



    
    #[Route('/teammate', name: 'tournament_teammate')]
    public function teammate(): Response
    {
        return $this->render('esports_all_views/tournaments/tournaments-teammate.html.twig');
    }


   
    #[Route('/teams', name: 'tournament_teams', methods: ['GET'])]
    public function teams(): Response
    {
        // Fetch all groups from the database
        $groups = $this->entityManager->getRepository(Group::class)->findAll();
    
        // Render the view and pass the groups to the template
        return $this->render('esports_all_views/tournaments/tournaments-teams.html.twig', [
            'groups' => $groups,
        ]);
    }

}
