<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commande')]
final class CommandeController extends AbstractController
{
    #[Route(name: 'app_commande_index', methods: ['GET', 'POST'])]
    public function index(Request $request, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            $this->addFlash('success', 'Commande ajoutée avec succès.');

            return $this->redirectToRoute('app_commande_index');
        }

        $commandes = $commandeRepository->findAll();

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();

            $this->addFlash('success', 'Commande supprimée avec succès.');
        }

        return $this->redirectToRoute('app_commande_index');
    }

#[Route('/edit/{id}', name: 'app_commande_edit', methods: ['GET'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Commande modifiée avec succès.');

            return $this->redirectToRoute('app_commande_index');
        }

        $this->addFlash('error', 'Erreur lors de la modification de la commande.');

        return $this->redirectToRoute('app_commande_index');
    }
}
