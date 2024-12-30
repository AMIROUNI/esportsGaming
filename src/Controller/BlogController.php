<?php

namespace App\Controller;

use App\Repository\ContenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/blog')]
class BlogController extends AbstractController
{
     
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'app_blog')]
    public function features(): Response
    {
        return $this->render('esports_all_views/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    #[Route('/news', name: 'app_contenu_news', methods: ['GET'])]
    public function news(ContenuRepository $contenuRepository): Response
    {
        $contenus = $contenuRepository->findAll();
        $c=$this->entityManager->getRepository(ContenuController::class)->findAll();
    
        return $this->render('esports_all_views/blog/news.html.twig', [
            'contenus' => $c,
        ]);
    }


    #[Route('/blog_list', name: 'blog_list')]
    public function blog_list(): Response
    {
        return $this->render('esports_all_views/blog/blog-list.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
  
    #[Route('/blog_grid', name: 'blog_grid')]
    public function blog_grid(): Response
    {
        return $this->render('esports_all_views/blog/blog-grid.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    #[Route('/blog_fullwidth', name: 'blog_fullwidth')]
    public function blog_fullwidth(): Response
    {
        return $this->render('esports_all_views/blog/blog-fullwidth.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }


    #[Route('/blog_article', name: 'blog_article')]
    public function blog_article(): Response
    {
        return $this->render('esports_all_views/blog/blog-article.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }


}