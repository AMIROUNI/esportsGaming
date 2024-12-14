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
    #[Route('/', name: 'app_game_index', methods: ['GET'])]
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('game/index.html.twig', [
            'games' => $gameRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_game_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $game = new Game();
    $form = $this->createForm(GameType::class, $game);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle the image upload
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $newFilename = uniqid() . '.' . $imageFile->guessExtension();

            // Move the file to the directory where images are stored
            $imageFile->move(
                $this->getParameter('game_images_directory'),
                $newFilename
            );

            // Update the image field with the new filename
            $game->setImage($newFilename);
        }

        $entityManager->persist($game);
        $entityManager->flush();

        return $this->redirectToRoute('app_game_index');
    }

    return $this->render('game/new.html.twig', [
        'game' => $game,
        'form' => $form,
    ]);
}

#[Route('/{id}/edit', name: 'app_game_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Game $game, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(GameType::class, $game);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $newFilename = uniqid() . '.' . $imageFile->guessExtension();
            $imageFile->move(
                $this->getParameter('game_images_directory'),
                $newFilename
            );
            $game->setImage($newFilename);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_game_index');
    }

    return $this->render('game/edit.html.twig', [
        'game' => $game,
        'form' => $form,
    ]);
}
}
