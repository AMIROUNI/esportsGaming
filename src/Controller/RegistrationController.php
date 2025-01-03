<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    
    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;

    public function __construct(
        EmailVerifier $emailVerifier,
        MailerInterface $mailer,
        EntityManagerInterface $entityManager
    ) {
        $this->emailVerifier = $emailVerifier;
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($user->getEmail())) {
                throw new \InvalidArgumentException("L'utilisateur doit avoir une adresse email valide.");
            }

            // Hash du mot de passe
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Sauvegarde de l'utilisateur
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // Envoi de l'email de confirmation
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('amirouni162@gmail.com', 'Amir EL_OUNI'))
                    ->to($user->getEmail())
                    ->subject('Veuillez confirmer votre adresse e-mail')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                    ->context([
                        'userEmail' => $user->getEmail(), // Use the same key name as in EmailVerifier
                    ])
            );
            

            return $this->redirectToRoute('app_esports_accueil_');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $email = $request->query->get('email'); // Retrieve 'email' from query parameters
        if (!$email) {
            throw $this->createNotFoundException('Email utilisateur manquant.');
        }
    
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
    
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }
    
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));
            return $this->redirectToRoute('app_register');
        }
    
        $this->addFlash('success', 'Votre adresse email a été vérifiée.');
        return $this->redirectToRoute('app_esports_accueil_');
    }
    


    #[Route('/test', name: 'app_test_email')]
    public function testEmail(): Response
    {
        $email = (new TemplatedEmail())
            ->from('test@example.com')
            ->to('recipient@example.com')
            ->subject('Test Email')
            ->htmlTemplate('emails/test_email.html.twig')
            ->context(['message' => 'Ceci est un email de test.']);

        $this->mailer->send($email);

        return new Response('Email envoyé avec succès.');
    }

    #[Route('/test/simple-email', name: 'test_simple_email')]
    public function testSimpleEmail(): Response
    {
        $email = (new TemplatedEmail())
            ->from('test@example.com')
            ->to('recipient@example.com')
            ->subject('Test Email Simple')
            ->htmlTemplate('emails/simple_email.html.twig')
            ->context(['message' => 'Ceci est un test d\'email simple.']);

        $this->mailer->send($email);

        return new Response('Email envoyé avec succès.');
    }
}
