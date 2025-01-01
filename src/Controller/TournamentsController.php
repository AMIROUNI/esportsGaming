<?php
// src/Controller/TournamentsController.php
namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Form\GroupType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tournaments')]
final class TournamentsController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'tournaments')]
    public function tournaments(): Response
    {
        return $this->render('esports_all_views/tournaments/tournaments.html.twig', [
            'controller_name' => 'TournoiController',
        ]);
    }

    #[Route('/teammate', name: 'tournament_teammate')]
    public function teammate(): Response
    {
        return $this->render('esports_all_views/tournaments/tournaments-teammate.html.twig');
    }

    #[Route('/teams', name: 'tournament_teams', methods: ['GET', 'POST'])]
    public function teams(Request $request, SessionInterface $session): Response
    {
        // Fetch all users (gamers) from the database
        $gamers = $this->entityManager->getRepository(User::class)->findAll();

        // Create a new group
        $group = new Group();
        $groupForm = $this->createForm(GroupType::class, $group, [
            'gamers' => $gamers, // Pass the list of users to the form
        ]);

        $groupForm->handleRequest($request);

        if ($groupForm->isSubmitted() && $groupForm->isValid()) {
            // Handle the logo file upload
            $logoFile = $groupForm->get('logo')->getData();
            if ($logoFile) {
                $newFilename = uniqid() . '.' . $logoFile->guessExtension();
                try {
                    $logoFile->move(
                        $this->getParameter('groupLog_images_directory'), // Define this in your config/services.yaml
                        $newFilename
                    );
                    $group->setLogo($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors du téléversement du logo.');
                }
            }

            // Save the group to the database
            $this->entityManager->persist($group);
            $this->entityManager->flush();

            $this->addFlash('success', 'Groupe créé avec succès!');
            return $this->redirectToRoute('tournament_teams');
        }

        // Fetch all groups from the database
        $groups = $this->entityManager->getRepository(Group::class)->findAll();

        // Render the view and pass the groups and form to the template
        return $this->render('esports_all_views/tournaments/tournaments-teams.html.twig', [
            'groups' => $groups,
            'groupForm' => $groupForm->createView(),
        ]);
    }

    #[Route('/teams/{id}/participate', name: 'tournament_teams_participate', methods: ['POST'])]
    public function participate(Group $group, SessionInterface $session): Response
    {
        // Get the current logged-in user's email from the session
        $userEmail = $session->get('user_id');
        if (!$userEmail) {
            $this->addFlash('error', 'Vous devez être connecté pour participer.');
            return $this->redirectToRoute('app_login');
        }

        // Find the user by email
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $userEmail]);
        if (!$user) {
            $this->addFlash('error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('tournament_teams');
        }

        // Check if the group has reached the maximum number of participants
        if ($group->getGamer()->count() >= Group::MAX_GAMERS) {
            $this->addFlash('error', 'Le groupe est complet.');
            return $this->redirectToRoute('tournament_teams');
        }

        // Add the user to the group's gamers
        $group->addGamer($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'Vous avez rejoint le groupe avec succès!');
        return $this->redirectToRoute('tournament_teams');
    }
}