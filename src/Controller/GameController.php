<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game')]
class GameController extends AbstractController
{
    #[Route('/', name: 'app_game_index', methods: ['GET', 'POST'])]
    public function index(GameRepository $gameRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Gestion de l'ajout d'un jeu
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleImageUpload($form, $game);
            $entityManager->persist($game);
            $entityManager->flush();

            $this->addFlash('success', 'Nouveau jeu ajouté avec succès !');
            return $this->redirectToRoute('app_game_index');
        }

        // Récupération de tous les jeux
        $games = $gameRepository->findAll();

        return $this->render('game/index.html.twig', [
            'games' => $games,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_game_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('image')->getData()) {
                $this->handleImageUpload($form, $game);
            }
            $entityManager->flush();

            $this->addFlash('success', 'Jeu modifié avec succès !');
            return $this->redirectToRoute('app_game_index');
        }

        return $this->render('game/edit.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_game_delete', methods: ['POST'])]
    public function delete(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $game->getId(), $request->request->get('_token'))) {
            $entityManager->remove($game);
            $entityManager->flush();
            $this->addFlash('success', 'Jeu supprimé avec succès !');
        }

        return $this->redirectToRoute('app_game_index');
    }

    /**
     * Méthode privée pour gérer l'upload de l'image.
     */
    private function handleImageUpload($form, Game $game): void
    {
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $newFilename = uniqid() . '.' . $imageFile->guessExtension();
            try {
                $imageFile->move(
                    $this->getParameter('game_images_directory'),
                    $newFilename
                );
                $game->setImage($newFilename);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'upload de l\'image : ' . $e->getMessage());
            }
        }
    }
}
