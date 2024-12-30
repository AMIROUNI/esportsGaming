<?php

namespace App\Controller;

use App\Entity\Tournoi;
use App\Form\TournoiType;
use App\Repository\TournoiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tournoi')]
class TournoiController extends AbstractController
{
    #[Route('', name: 'app_tournoi_index', methods: ['GET', 'POST'])]
    public function index(Request $request, TournoiRepository $tournoiRepository, EntityManagerInterface $entityManager): Response
    {
        $tournoi = new Tournoi();
        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tournoi);
            $entityManager->flush();

            $this->addFlash('success', 'Tournoi créé avec succès!');

            return $this->redirectToRoute('app_tournoi_index');
        }

        return $this->render('tournoi/index.html.twig', [
            'form' => $form->createView(),
            'tournois' => $tournoiRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tournoi_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Tournoi $tournoi, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(TournoiType::class, $tournoi);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        $this->addFlash('success', 'Tournoi modifié avec succès!');

        return $this->redirectToRoute('app_tournoi_index');
    }

    return $this->render('tournoi/edit.html.twig', [
        'form' => $form->createView(),
        'tournoi' => $tournoi,
    ]);
}


    #[Route('/{id}/delete', name: 'app_tournoi_delete', methods: ['POST'])]
    public function delete(Request $request, Tournoi $tournoi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tournoi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tournoi);
            $entityManager->flush();

            $this->addFlash('success', 'Tournoi supprimé avec succès!');
        }

        return $this->redirectToRoute('app_tournoi_index');
    }
}