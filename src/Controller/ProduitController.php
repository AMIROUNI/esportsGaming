<?php
// src/Controller/ProduitController.php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\LpRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET', 'POST'])]
    public function index(ProduitRepository $produitRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Gestion de l'ajout d'un produit
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleImageUpload($form, $produit);
            $entityManager->persist($produit);
            $entityManager->flush();

            $this->addFlash('success', 'Nouveau produit ajouté avec succès !');
            return $this->redirectToRoute('app_produit_index');
        }

        // Récupération de tous les produits
        $produits = $produitRepository->findAll();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('image')->getData()) {
                $this->handleImageUpload($form, $produit);
            }
            $entityManager->flush();

            $this->addFlash('success', 'Produit modifié avec succès !');
            return $this->redirectToRoute('app_produit_index');
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_produit_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
            $this->addFlash('success', 'Produit supprimé avec succès !');
        }

        return $this->redirectToRoute('app_produit_index');
    }

    /**
     * Méthode privée pour gérer l'upload de l'image.
     */
    private function handleImageUpload($form, Produit $produit): void
    {
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $newFilename = uniqid() . '.' . $imageFile->guessExtension();
            try {
                $imageFile->move(
                    $this->getParameter('produit_images_directory'),
                    $newFilename
                );
                $produit->setImage($newFilename);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'upload de l\'image : ' . $e->getMessage());
            }
        }
    }




#[Route('/produit/{id}', name: 'app_lp_by_produit', methods: ['GET'])]
public function findByProduit(Produit $produit, LpRepository $lpRepository): Response
{
    $lps = $lpRepository->findBy(['produit' => $produit]);

    return $this->render('lp/index.html.twig', [
        'lps' => $lps,
        'produit' => $produit,
    ]);
}

}
