<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function store_catalog_alt(): Response
    {
        return $this->render('esports_all_views/store/store-catalog-alt.html.twig', [
            'controller_name' => 'StoreController',
        ]);
    }


    #[Route('/cart', name: 'store_cart')]
    public function store_cart(): Response
    {
        return $this->render('esports_all_views/store/store-cart.html.twig', [
            'controller_name' => 'StoreController',
        ]);
    }


}
