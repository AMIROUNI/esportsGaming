<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\User;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/store')]
class StoreController extends AbstractController
{

    #[Route('', name: 'app_store')]
    public function features(): Response
    {
        return $this->render('esports_all_views/store/store.html.twig', [
            'controller_name' => 'StoreController',
        ]);
    }

    #[Route('/product', name: 'store_product')]
    public function store_product(): Response
    {
        return $this->render('esports_all_views/store/store-product.html.twig', [
            'controller_name' => 'StoreController',
        ]);
    }

    #[Route('/checkout', name: 'store_checkout')]
    public function store_checkout(): Response
    {
        return $this->render('esports_all_views/store/store-checkout.html.twig', [
            'controller_name' => 'StoreController',
        ]);
    }


    #[Route('/catalog', name: 'store_catalog')]
    public function store_catalog(): Response
    {
        return $this->render('esports_all_views/store/store-catalog.html.twig', [
            'controller_name' => 'StoreController',
        ]);
    }

    #[Route('/catalog_alt', name: 'store_catalog_alt')]
    public function store_catalog_alt( SessionInterface $session,EntityManagerInterface $entityManager, ProduitRepository $produitRepository): Response
    {     


        $userEmail = $session->get('user_id');

        if (!$userEmail) {
            $this->addFlash('error', 'You must be logged in to view your card.');
            return $this->redirectToRoute('app_login'); // Redirect to login page
        }
    
        // Retrieve the user by email
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $userEmail]);
        if (!$user) {
            $this->addFlash('error', 'User not found.');
            return $this->redirectToRoute('app_login'); // Redirect to login page
        }
        

        // Retrieve the user's card
        $card = $user->getCard();
        if (!$card) {

            $card= new Card();
        }
      #  if (!$card || $card->getProduits()->isEmpty()) {
       #   $this->addFlash('info', 'Your card is empty.');
       #  return $this->redirectToRoute('store_product'); // Redirect to products page
       # }


       

        $produits = $produitRepository->findAll();
        return $this->render('esports_all_views/store/store-catalog-alt.html.twig', [
            'controller_name' => 'StoreController',
            'produits'=>$produits,
            'card' => $card,
            'produitsCard' => $card->getProduits(),
        ]);
    }


    #[Route('/cart', name: 'store_cart')]
    public function store_cart(): Response
    {
        return $this->render('esports_all_views/store/store-cart.html.twig', [
            'controller_name' => 'StoreController',
        ]);
    }


    #[Route('/product', name: 'store_product', methods: ['GET'])]
public function storeProduct(ProduitRepository $produitRepository): Response
{
    // Fetch all products from the database
    $produits = $produitRepository->findAll();

    return $this->render('esports_all_views/store/store-product.html.twig', [
        'produits' => $produits, // Pass the list of products to the template
    ]);
}


}
