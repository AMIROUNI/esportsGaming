<?php

namespace App\Controller;

use App\Entity\DemandeDeProgrammeC;
use App\Form\DemandeDeProgrammeCType;
use App\Repository\DemandeDeProgrammeCRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/demande/de/programme/c')]
final class DemandeDeProgrammeCController extends AbstractController
{
    #[Route(name: 'app_demande_de_programme_c_index', methods: ['GET'])]
    public function index(DemandeDeProgrammeCRepository $demandeDeProgrammeCRepository): Response
    {
        return $this->render('demande_de_programme_c/index.html.twig', [
            'demande_de_programme_cs' => $demandeDeProgrammeCRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_demande_de_programme_c_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $demandeDeProgrammeC = new DemandeDeProgrammeC();
        $form = $this->createForm(DemandeDeProgrammeCType::class, $demandeDeProgrammeC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($demandeDeProgrammeC);
            $entityManager->flush();

            return $this->redirectToRoute('app_demande_de_programme_c_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demande_de_programme_c/new.html.twig', [
            'demande_de_programme_c' => $demandeDeProgrammeC,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_de_programme_c_show', methods: ['GET'])]
    public function show(DemandeDeProgrammeC $demandeDeProgrammeC): Response
    {
        return $this->render('demande_de_programme_c/show.html.twig', [
            'demande_de_programme_c' => $demandeDeProgrammeC,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_demande_de_programme_c_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DemandeDeProgrammeC $demandeDeProgrammeC, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DemandeDeProgrammeCType::class, $demandeDeProgrammeC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_demande_de_programme_c_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demande_de_programme_c/edit.html.twig', [
            'demande_de_programme_c' => $demandeDeProgrammeC,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_de_programme_c_delete', methods: ['POST'])]
    public function delete(Request $request, DemandeDeProgrammeC $demandeDeProgrammeC, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demandeDeProgrammeC->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($demandeDeProgrammeC);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_demande_de_programme_c_index', [], Response::HTTP_SEE_OTHER);
    }
}
