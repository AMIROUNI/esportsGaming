<?php
// src/Controller/ParticipationTournoiController.php

namespace App\Controller;

use App\Entity\Matches;
use App\Entity\ParticipationTournoi;
use App\Entity\Tournoi;
use App\Form\MatchesType;
use App\Form\ParticipationTournoiType;
use App\Repository\ParticipationTournoiRepository;
use App\Repository\TournoiRepository;
use App\Repository\GroupRepository;
use App\Repository\MatchesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ParticipationTournoiController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/participation/{id}', name: 'app_participation_tournoi_index')]
    public function index(
        int $id, 
        TournoiRepository $tournoiRepository, 
        ParticipationTournoiRepository $participationTournoiRepository, 
        MatchesRepository $matchRepository, 
        Request $request, 
        GroupRepository $groupRepository
    ): Response {
        // Récupérer le tournoi par son ID
        $tournoi = $tournoiRepository->find($id);
        if (!$tournoi) {
            throw $this->createNotFoundException('Le tournoi n\'existe pas.');
        }
    
        // Récupérer toutes les participations pour ce tournoi
        $participations = $participationTournoiRepository->findBy(['tournoi' => $tournoi]);
    
        // Récupérer tous les matches pour ce tournoi
        $matches = $matchRepository->findBy(['tournoi' => $tournoi]);
    
        // Formulaire pour ajouter une nouvelle participation
        $participation = new ParticipationTournoi();
        $form = $this->createForm(ParticipationTournoiType::class, $participation, [
            'tournoi' => $tournoi,
            'groups' => $groupRepository->findAll(),
        ]);
        $match=new Matches();
        $matchForm=$this->createForm(MatchesType::class, $match);
        $matchForm->handleRequest($request);
        if ($matchForm->isSubmitted() && $matchForm->isValid()) {
            $match->setTournoi($tournoi);
            $match->setScoreA(0);
            $match->setScoreB(0);
            $this->entityManager->persist($match);
            $this->entityManager->flush();
            $this->addFlash('success', 'La mathe a été ajoutée avec succès.');
        }
    
        // Gérer les soumissions de formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $participation->setTournoi($tournoi);
            $this->entityManager->persist($participation);
            $this->entityManager->flush();
    
            // Message de confirmation
            $this->addFlash('success', 'La participation a été ajoutée avec succès.');
    
            // Redirection vers la même page
            return $this->redirectToRoute('app_participation_tournoi_index', ['id' => $id]);
        }
    
        // Rendu de la vue
        return $this->render('participation_tournoi/index.html.twig', [
            'tournoi' => $tournoi,
            'participations' => $participations,
            'matches' => $matches,
            'form' => $form->createView(),
            'matchForm'=>$matchForm->createView(),
        ]);
    }
    

    #[Route('/participation/{id}/modifier', name: 'app_participation_tournoi_modifier')]
    public function modifier(int $id, ParticipationTournoiRepository $participationTournoiRepository, Request $request, GroupRepository $groupRepository): Response
    {
        $participation = $participationTournoiRepository->find($id);
        if (!$participation) {
            throw $this->createNotFoundException('La participation n\'existe pas.');
        }

        $form = $this->createForm(ParticipationTournoiType::class, $participation, [
            'groups' => $groupRepository->findAll()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'La participation a été modifiée avec succès.');
            return $this->redirectToRoute('app_participation_tournoi_index', ['id' => $participation->getTournoi()->getId()]);
        }

        return $this->render('participation_tournoi/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/participation/{id}/supprimer', name: 'app_participation_tournoi_supprimer')]
    public function supprimer(int $id, ParticipationTournoiRepository $participationTournoiRepository): Response
    {
        $participation = $participationTournoiRepository->find($id);
        if (!$participation) {
            throw $this->createNotFoundException('La participation n\'existe pas.');
        }

        $this->entityManager->remove($participation);
        $this->entityManager->flush();

        $this->addFlash('success', 'La participation a été supprimée avec succès.');
        return $this->redirectToRoute('app_participation_tournoi_index', ['id' => $participation->getTournoi()->getId()]);
    }





    #[Route('/match/{id}/delete', name: 'app_match_delete')]
public function deleteMatch(int $id, MatchesRepository $matchesRepository): Response
{
    // Retrieve the match by its ID
    $match = $matchesRepository->find($id);
    if (!$match) {
        throw $this->createNotFoundException('The match does not exist.');
    }

    // Store the tournament ID for redirection
    $tournoiId = $match->getTournoi()->getId();

    // Remove the match
    $this->entityManager->remove($match);
    $this->entityManager->flush();

    // Add a success flash message
    $this->addFlash('success', 'The match has been successfully deleted.');

    // Redirect back to the tournament's participation page
    return $this->redirectToRoute('app_participation_tournoi_index', ['id' => $tournoiId]);
}



#[Route('/match/{id}/edit', name: 'app_match_edit')]
public function editMatch(
    int $id, 
    MatchesRepository $matchesRepository, 
    Request $request
): Response {
    // Retrieve the match by its ID
    $match = $matchesRepository->find($id);
    if (!$match) {
        throw $this->createNotFoundException('The match does not exist.');
    }

    // Create the form for editing the match
    $form = $this->createForm(MatchesType::class, $match);
    $form->handleRequest($request);

    // Handle form submission
    if ($form->isSubmitted() && $form->isValid()) {
        $this->entityManager->flush();

        $this->addFlash('success', 'The match has been successfully updated.');

        // Redirect back to the tournament's participation page
        return $this->redirectToRoute('app_participation_tournoi_index', [
            'id' => $match->getTournoi()->getId(),
        ]);
    }

    // Render the edit form view
    return $this->render('participation_tournoi/editMatch.html.twig', [
        'form' => $form->createView(),
        'match' => $match,
    ]);
}

}
