<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Produit;
use App\Entity\User;
use App\Form\CardType;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/card')]
final class CardController extends AbstractController
{
    #[Route(name: 'app_card_index', methods: ['GET'])]
    public function index(CardRepository $cardRepository): Response
    {
        return $this->render('card/index.html.twig', [
            'cards' => $cardRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_card_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $card = new Card();
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($card);
            $entityManager->flush();

            return $this->redirectToRoute('app_card_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('card/new.html.twig', [
            'card' => $card,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_card_show', methods: ['GET'])]
    public function show(Card $card): Response
    {
        return $this->render('card/show.html.twig', [
            'card' => $card,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_card_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Card $card, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_card_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('card/edit.html.twig', [
            'card' => $card,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_card_delete', methods: ['POST'])]
    public function delete(Request $request, Card $card, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$card->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($card);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_card_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/add/{productId}', name: 'app_card_add_product', methods: ['POST', 'GET'])]
    public function addProductToCard(
        int $productId,
        EntityManagerInterface $entityManager,
        SessionInterface $session
    ): Response {
        // Retrieve the user email from the session
        $userEmail = $session->get('user_id');
        
        if (!$userEmail) {
            $this->addFlash('error', 'You must be logged in to add products to your card.');
            return $this->redirectToRoute('app_login'); // Redirect to the login page
        }
    
        // Retrieve the user from the database using the email
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $userEmail]);
        if (!$user) {
            $this->addFlash('error', 'User not found.');
            return $this->redirectToRoute('app_login'); // Redirect to the login page
        }
    
        // Retrieve or create the user's card
        $card = $user->getCard();
        if (!$card) {
            $card = new Card();
            $card->setUser($user);
            $entityManager->persist($card);
        }
    
        // Retrieve the product by ID
        $product = $entityManager->getRepository(Produit::class)->find($productId);
        if (!$product) {
            $this->addFlash('error', 'Product not found.');
            return $this->redirectToRoute('store_products'); // Redirect back to the products page
        }
    
        // Add the product to the card
        $card->addProduit($product);
        $entityManager->persist($card);
        $entityManager->flush();
    
        $this->addFlash('success', 'Product added to your card.');
    
        return $this->redirectToRoute('store_catalog_alt'); // Redirect back to the products page
    }
    
    

}
