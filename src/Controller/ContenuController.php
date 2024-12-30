<?php

namespace App\Controller;

use App\Entity\Contenu;
use App\Form\ContenuType;
use App\Repository\ContenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contenu')]
final class ContenuController extends AbstractController
{
    #[Route('/', name: 'app_contenu_index', methods: ['GET', 'POST'])]
    public function index(ContenuRepository $contenuRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $contenu = new Contenu();
        $form = $this->createForm(ContenuType::class, $contenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleImageUpload($form, $contenu);
            $contenu->setData(new \DateTime());
            $entityManager->persist($contenu);
            $entityManager->flush();

            $this->addFlash('success', 'Nouveau contenu ajouté avec succès !');
            return $this->redirectToRoute('app_contenu_index');
        }

        $contenus = $contenuRepository->findAll();

        return $this->render('contenu/index.html.twig', [
            'contenus' => $contenus,
            'form' => $form->createView(),
        ]);
    }

    private function handleImageUpload($form, Contenu $contenu): void
    {
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $newFilename = uniqid() . '.' . $imageFile->guessExtension();
            try {
                $imageFile->move(
                    $this->getParameter('contenu_images_directory') ,
                    $newFilename
                );
                $contenu->setImage($newFilename);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'upload de l\'image : ' . $e->getMessage());
            }
        }
    }

    #[Route('/new', name: 'app_contenu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contenu = new Contenu();
        $form = $this->createForm(ContenuType::class, $contenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contenu);
            $entityManager->flush();

            return $this->redirectToRoute('app_contenu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contenu/new.html.twig', [
            'contenu' => $contenu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contenu_show', methods: ['GET'])]
    public function show(Contenu $contenu): Response
    {
        return $this->render('contenu/show.html.twig', [
            'contenu' => $contenu,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contenu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contenu $contenu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContenuType::class, $contenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('image')->getData()) {
                $this->handleImageUpload($form, $contenu);
            }
            $entityManager->flush();

            $this->addFlash('success', 'contenu modifié avec succès !');
            return $this->redirectToRoute('app_contenu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contenu/edit.html.twig', [
            'contenu' => $contenu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contenu_delete', methods: ['POST'])]
    public function delete(Request $request, Contenu $contenu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contenu->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($contenu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contenu_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/news', name: 'app_contenu_news', methods: ['GET'])]
public function news(ContenuRepository $contenuRepository): Response
{
    $contenus = $contenuRepository->findAll();

    return $this->render('esports_all_views/blog/news.html.twig', [
        'contenus' => $contenus,
    ]);
}
}
