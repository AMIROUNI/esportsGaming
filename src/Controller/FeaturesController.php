<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/features')]
class FeaturesController extends AbstractController
{
    #[Route('', name: 'app_features')]
    public function features(): Response
    {
        return $this->render('esports_all_views/index.html.twig', [
            'controller_name' => 'FeaturesController',
        ]);
    }

    #[Route('/forum', name: 'forum')]
    public function forum(): Response
    {
        return $this->render('esports_all_views/forum.html.twig', [
            'controller_name' => 'FeaturesController',
        ]);
    }


    
    #[Route('/forum_single_topic', name: 'forum_single_topic')]
    public function forum_topics(): Response
    {
        return $this->render('esports_all_views/forum-single-topic.html.twig', [
            'controller_name' => 'FeaturesController',
        ]);
    }
    #[Route('/elements', name: 'elements')]
    public function elements(): Response
    {
        return $this->render('esports_all_views/elements.html.twig', [
            'controller_name' => 'FeaturesController',
        ]);
    }


    #[Route('/coming_soon', name: 'coming_soon')]
    public function coming_soon(): Response
    {
        return $this->render('esports_all_views/coming-soon.html.twig', [
            'controller_name' => 'FeaturesController',
        ]);
    }

    #[Route('/offline', name: 'offline')]
    public function offline(): Response
    {
        return $this->render('esports_all_views/offline.html.twig', [
            'controller_name' => 'FeaturesController',
        ]);
    }

    #[Route('/error', name: 'error')]
    public function error(): Response
    {
        return $this->render('esports_all_views/404.html.twig', [
            'controller_name' => 'FeaturesController',
        ]);
    }
}
