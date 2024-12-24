<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User1Type;
use App\Form\UserType;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
final class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    // Injecting EntityManager via the constructor
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository,GroupRepository $groupRepository,Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        // Creating the registration form
        $form = $this->createForm(UserType::class, $user);

        // Handling the request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hashing the password
            $hashedPassword = $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData());
            $user->setPassword($hashedPassword);

            // Persisting the user to the database
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // Adding a success flash message
            $this->addFlash('success', 'Registration successful! You can now log in.');

            // Redirecting to the home page after registration
            return $this->redirectToRoute('app_esports_accueil_');
        }
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'user' => $user,
            'userForm' => $form->createView(),
            'groups'=>$groupRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
