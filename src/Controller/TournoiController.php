<?php

namespace App\Controller;

use App\Entity\Tournoi;
use App\Form\TournoiType;
use App\Repository\TournoiRepository;
use Doctrine\DBAL\Schema\Index;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tournaments')]
final class TournoiController extends AbstractController
{
    /*
    #[Route('/index',name: 'app_tournoi_index', methods: ['GET'])]
    public function index(TournoiRepository $tournoiRepository): Response
    {
        return $this->render('tournoi/index.html.twig', [
            'tournois' => $tournoiRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tournoi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tournoi = new Tournoi();
        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tournoi);
            $entityManager->flush();

            return $this->redirectToRoute('app_tournoi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tournoi/new.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tournoi_show', methods: ['GET'])]
    public function show(Tournoi $tournoi): Response
    {
        return $this->render('tournoi/show.html.twig', [
            'tournoi' => $tournoi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tournoi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tournoi $tournoi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tournoi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tournoi/edit.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tournoi_delete', methods: ['POST'])]
    public function delete(Request $request, Tournoi $tournoi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tournoi->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tournoi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tournoi_index', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('', name: 'tournaments')]
    public function tournaments(): Response
    {
        return $this->render('esports_all_views/tournaments.html.twig', [
            'controller_name' => 'TournoiController',
        ]);
    }



    
    #[Route('/teammate', name: 'tournament_teammate')]
    public function teammate(): Response
    {
        return $this->render('esports_all_views/tournaments-teammate.html.twig');
    }


    #[Route('/teams', name: 'tournament_teams')]
    public function teams(): Response
    {
        return $this->render('esports_all_views/tournaments-teams.html.twig');
    }
*/
}
