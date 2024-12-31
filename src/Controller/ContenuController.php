<?php

namespace App\Controller;

use App\Entity\Contenu;
use App\Form\ContenuType;
use App\Repository\BlogCategoryRepository;
use App\Repository\ContenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/contenu')]
 class ContenuController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
   // src/Controller/ContenuController.php

   #[Route('/', name: 'app_contenu_index', methods: ['GET', 'POST'])]
   public function index(ContenuRepository $contenuRepository, Request $request, BlogCategoryRepository $categoryRepository): Response
   {
       $categories = $categoryRepository->findAll(); // Get categories
       $contenu = new Contenu();
       $form = $this->createForm(ContenuType::class, $contenu, [
           'categories' => $categories, // Pass categories to the form
       ]);

       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           // Handle image upload
           $this->handleImageUpload($form, $contenu);

           // Handle category selection
           $selectedCategories = $form->get('categories')->getData();
           foreach ($selectedCategories as $category) {
               $contenu->addCategory($category);
           }

           // Set the data and persist the content
           $contenu->setData(new \DateTime());
           $this->entityManager->persist($contenu);
           $this->entityManager->flush();

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

    #[Route('/{id}', name: 'app_contenu_show',requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Contenu $contenu): Response
    {
        return $this->render('contenu/show.html.twig', [
            'contenu' => $contenu,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contenu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contenu $contenu, EntityManagerInterface $entityManager, BlogCategoryRepository $categoryRepository): Response
    {

        $categories=$categoryRepository->findAll();
        $form = $this->createForm(ContenuType::class, $contenu, [
            'categories' => $categories, // Pass categories to the form
        ]);

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
    public function news(ContenuRepository $contenuRepository, SessionInterface $session): Response
    {
        // Récupérer la variable de session 'user_id'
        $userId = $session->get('user_id');
    
        // Récupérer les contenus
        $contenus = $contenuRepository->findAll();
    
        return $this->render('esports_all_views/blog/news.html.twig', [
            'contenus' => $contenus,
            'user_id' => $userId, // Passez l'ID utilisateur à la vue
        ]);
    }
    


    #[Route('/article/{id}', name: 'blog_article', methods: ['GET'], requirements: ['id' => '\d+'])]
public function blog_article($id,ContenuRepository $contenuRepository, SessionInterface $session): Response
{     
    $userId = $session->get('user_id');
    $contenu = $contenuRepository->find($id);
    return $this->render('esports_all_views/blog/blog-article.html.twig', [
        'contenu' => $contenu,
        'user_id' => $userId, 
    ]);
}
}
