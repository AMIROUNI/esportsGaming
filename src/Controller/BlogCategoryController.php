<?php

namespace App\Controller;

use App\Entity\BlogCategory;
use App\Form\BlogCategoryType;
use App\Repository\BlogCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/blogCategory')]
final class BlogCategoryController extends AbstractController
{
    #[Route(name: 'app_blog_category_index', methods: ['GET'])]
    public function index(BlogCategoryRepository $blogCategoryRepository): Response
    {
        return $this->render('blog_category/index.html.twig', [
            'blog_categories' => $blogCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_blog_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $blogCategory = new BlogCategory();
        $form = $this->createForm(BlogCategoryType::class, $blogCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($blogCategory);
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog_category/new.html.twig', [
            'blog_category' => $blogCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_category_show', methods: ['GET'])]
    public function show(BlogCategory $blogCategory): Response
    {
        return $this->render('blog_category/show.html.twig', [
            'blog_category' => $blogCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_blog_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BlogCategory $blogCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogCategoryType::class, $blogCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog_category/edit.html.twig', [
            'blog_category' => $blogCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_category_delete', methods: ['POST'])]
    public function delete(Request $request, BlogCategory $blogCategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blogCategory->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($blogCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blog_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
