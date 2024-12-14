<?php

namespace App\Controller;

use App\Entity\ProgrammeCoaching;
use App\Form\ProgrammeCoachingType;
use App\Repository\ProgrammeCoachingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/programme-coaching')]
class ProgrammeCoachingController extends AbstractController
{
    #[Route('/', name: 'app_programme_coaching_index', methods: ['GET', 'POST'])]
    public function index(ProgrammeCoachingRepository $programmeCoachingRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Gestion de l'ajout d'un programme de coaching
        $programmeCoaching = new ProgrammeCoaching();
        $form = $this->createForm(ProgrammeCoachingType::class, $programmeCoaching);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($programmeCoaching);
            $entityManager->flush();

            $this->addFlash('success', 'Nouveau programme de coaching ajouté avec succès !');
            return $this->redirectToRoute('app_programme_coaching_index');
        }

        // Récupération de tous les programmes de coaching
        $programmes = $programmeCoachingRepository->findAll();

        return $this->render('programme_coaching/index.html.twig', [
            'programmes' => $programmes,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_programme_coaching_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProgrammeCoaching $programmeCoaching, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProgrammeCoachingType::class, $programmeCoaching);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Programme de coaching modifié avec succès !');
            return $this->redirectToRoute('app_programme_coaching_index');
        }

        return $this->render('programme_coaching/edit.html.twig', [
            'programme' => $programmeCoaching,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_programme_coaching_delete', methods: ['GET','POST'])]
    public function delete(Request $request, ProgrammeCoaching $programmeCoaching, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($programmeCoaching);
        $entityManager->flush();

        $this->addFlash('success', 'Programme de coaching supprimé avec succès !');

        return $this->redirectToRoute('app_programme_coaching_index');
    }
}
