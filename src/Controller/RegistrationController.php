<?php

// src/Controller/RegistrationController.php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    // Injecting EntityManager via the constructor
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        // Creating a new instance of the User entity
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

        // Rendering the registration form
        return $this->render('registration/register.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }
}
