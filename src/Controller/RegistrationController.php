<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $entityManager->persist($user);
            $entityManager->flush();

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
    (new TemplatedEmail())
        ->from(new Address('amirouni162@gmail.com', 'Amir EL_OUNI'))
        ->to((string) $user->getEmail())
        ->subject('Please Confirm your Email')
        ->htmlTemplate('registration/confirmation_email.html.twig')
        ->context([
            'user' => $user,
        ])
);

        
        

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_esports_accueil_');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            /** @var User $user */
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }



    #[Route('/test')]
    public function testEmail(EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find(1);
        
        $email = (new TemplatedEmail())
            ->from(new Address('amirouni162@gmail.com', 'Amir EL_OUNI'))
            ->to('test@example.com')
            ->subject('Test Email')
            ->htmlTemplate('registration/confirmation_email.html.twig')
            ->textTemplate('registration/confirmation_email.txt.twig')
            ->context([
                'signedUrl' => 'http://example.com/verify',
                'expiresAtMessageKey' => 'This link expires in {{ expiry }}.',
                'expiresAtMessageData' => ['expiry' => '24 hours'],
            ]);
        
        try {
            $htmlContent = $this->renderView($email->getHtmlTemplate(), $email->getContext());
            $textContent = $this->renderView($email->getTextTemplate(), $email->getContext());
            return new Response("HTML Content: $htmlContent\n\nText Content: $textContent");
        } catch (\Exception $e) {
            return new Response('Error rendering templates: ' . $e->getMessage());
        }
    }
    
}
