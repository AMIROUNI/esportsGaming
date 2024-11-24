<?php

namespace App\Controller;

use App\Entity\ParticipationTournoi;
use App\Form\ParticipationTournoiType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/participation/tournoi')]
final class ParticipationTournoiController extends AbstractController
{
    #[Route(name: 'app_participation_tournoi_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $participationTournois = $entityManager
            ->getRepository(ParticipationTournoi::class)
            ->findAll();

        return $this->render('participation_tournoi/index.html.twig', [
            'participation_tournois' => $participationTournois,
        ]);
    }

    #[Route('/new', name: 'app_participation_tournoi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $participationTournoi = new ParticipationTournoi();
        $form = $this->createForm(ParticipationTournoiType::class, $participationTournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participationTournoi);
            $entityManager->flush();

            return $this->redirectToRoute('app_participation_tournoi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('participation_tournoi/new.html.twig', [
            'participation_tournoi' => $participationTournoi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participation_tournoi_show', methods: ['GET'])]
    public function show(ParticipationTournoi $participationTournoi): Response
    {
        return $this->render('participation_tournoi/show.html.twig', [
            'participation_tournoi' => $participationTournoi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_participation_tournoi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ParticipationTournoi $participationTournoi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParticipationTournoiType::class, $participationTournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_participation_tournoi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('participation_tournoi/edit.html.twig', [
            'participation_tournoi' => $participationTournoi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participation_tournoi_delete', methods: ['POST'])]
    public function delete(Request $request, ParticipationTournoi $participationTournoi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participationTournoi->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($participationTournoi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_participation_tournoi_index', [], Response::HTTP_SEE_OTHER);
    }
}
