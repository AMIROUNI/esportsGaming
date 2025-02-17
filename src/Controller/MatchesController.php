<?php

namespace App\Controller;

use App\Entity\Matches;
use App\Form\MatchesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/matches')]
final class MatchesController extends AbstractController
{
    #[Route(name: 'app_matches_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $matches = $entityManager
            ->getRepository(Matches::class)
            ->findAll();

        return $this->render('matches/index.html.twig', [
            'matches' => $matches,
        ]);
    }

    #[Route('/new', name: 'app_matches_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $match = new Matches();
        $form = $this->createForm(MatchesType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($match);
            $entityManager->flush();

            return $this->redirectToRoute('app_matches_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('matches/new.html.twig', [
            'match' => $match,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_matches_show', methods: ['GET'])]
    public function show(Matches $match): Response
    {
        return $this->render('matches/show.html.twig', [
            'match' => $match,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_matches_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Matches $match, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MatchesType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_matches_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('matches/edit.html.twig', [
            'match' => $match,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_matches_delete', methods: ['POST'])]
    public function delete(Request $request, Matches $match, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$match->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($match);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_matches_index', [], Response::HTTP_SEE_OTHER);
    }
}
