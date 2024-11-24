<?php

namespace App\Controller;

use App\Entity\Gamer;
use App\Form\GamerType;
use App\Repository\GamerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gamer')]
final class GamerController extends AbstractController
{
    #[Route(name: 'app_gamer_index', methods: ['GET'])]
    public function index(GamerRepository $gamerRepository): Response
    {
        return $this->render('gamer/index.html.twig', [
            'gamers' => $gamerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_gamer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $gamer = new Gamer();
        $form = $this->createForm(GamerType::class, $gamer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($gamer);
            $entityManager->flush();

            return $this->redirectToRoute('app_gamer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gamer/new.html.twig', [
            'gamer' => $gamer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gamer_show', methods: ['GET'])]
    public function show(Gamer $gamer): Response
    {
        return $this->render('gamer/show.html.twig', [
            'gamer' => $gamer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gamer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gamer $gamer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GamerType::class, $gamer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_gamer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gamer/edit.html.twig', [
            'gamer' => $gamer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gamer_delete', methods: ['POST'])]
    public function delete(Request $request, Gamer $gamer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gamer->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($gamer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_gamer_index', [], Response::HTTP_SEE_OTHER);
    }
}
