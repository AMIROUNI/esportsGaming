<?php

namespace App\Controller;

use App\Entity\ProgrammeCoaching;
use App\Form\ProgrammeCoachingType;
use App\Repository\ProgrammeCoachingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/programme/coaching')]
final class ProgrammeCoachingController extends AbstractController
{
    #[Route(name: 'app_programme_coaching_index', methods: ['GET'])]
    public function index(ProgrammeCoachingRepository $programmeCoachingRepository): Response
    {
        return $this->render('programme_coaching/index.html.twig', [
            'programme_coachings' => $programmeCoachingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_programme_coaching_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $programmeCoaching = new ProgrammeCoaching();
        $form = $this->createForm(ProgrammeCoachingType::class, $programmeCoaching);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($programmeCoaching);
            $entityManager->flush();

            return $this->redirectToRoute('app_programme_coaching_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('programme_coaching/new.html.twig', [
            'programme_coaching' => $programmeCoaching,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_programme_coaching_show', methods: ['GET'])]
    public function show(ProgrammeCoaching $programmeCoaching): Response
    {
        return $this->render('programme_coaching/show.html.twig', [
            'programme_coaching' => $programmeCoaching,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_programme_coaching_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProgrammeCoaching $programmeCoaching, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProgrammeCoachingType::class, $programmeCoaching);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_programme_coaching_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('programme_coaching/edit.html.twig', [
            'programme_coaching' => $programmeCoaching,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_programme_coaching_delete', methods: ['POST'])]
    public function delete(Request $request, ProgrammeCoaching $programmeCoaching, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$programmeCoaching->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($programmeCoaching);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_programme_coaching_index', [], Response::HTTP_SEE_OTHER);
    }
}
