<?php
// src/Controller/ParticipationTournoiController.php

namespace App\Controller;

use App\Entity\ParticipationTournoi;
use App\Entity\Tournoi;
use App\Form\ParticipationTournoiType;
use App\Repository\ParticipationTournoiRepository;
use App\Repository\TournoiRepository;
use App\Repository\GroupRepository;
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
    public function index(int $id, TournoiRepository $tournoiRepository, ParticipationTournoiRepository $participationTournoiRepository, Request $request, GroupRepository $groupRepository): Response
    {
        $tournoi = $tournoiRepository->find($id);
        if (!$tournoi) {
            throw $this->createNotFoundException('Le tournoi n\'existe pas.');
        }

        // Récupérer toutes les participations pour ce tournoi
        $participations = $participationTournoiRepository->findBy(['tournoi' => $tournoi]);

        // Formulaire pour ajouter une nouvelle participation
        $participation = new ParticipationTournoi();
        $form = $this->createForm(ParticipationTournoiType::class, $participation, [
            'tournoi' => $tournoi,
            'groups' => $groupRepository->findAll()
        ]);

        // Formulaire de mise à jour ou suppression de participation
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participation->setTournoi($tournoi);
            $this->entityManager->persist($participation);
            $this->entityManager->flush();

            $this->addFlash('success', 'La participation a été ajoutée avec succès.');
            return $this->redirectToRoute('app_participation_tournoi_index', ['id' => $id]);
        }

        return $this->render('participation_tournoi/index.html.twig', [
            'tournoi' => $tournoi,
            'participations' => $participations,
            'form' => $form->createView(),
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
}
