<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\ParticipationTournoi;
use App\Entity\Tournoi;
use App\Entity\User;
use App\Enum\EtatDeParticipationTournoi;
use App\Form\TournoiType;
use App\Repository\MatchesRepository;
use App\Repository\TournoiRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tournoi')]
class TournoiController extends AbstractController
{
    #[Route('', name: 'app_tournoi_index', methods: ['GET', 'POST'])]
    public function index(Request $request, TournoiRepository $tournoiRepository, EntityManagerInterface $entityManager): Response
    {
        $tournoi = new Tournoi();
        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tournoi);
            $entityManager->flush();

            $this->addFlash('success', 'Tournoi créé avec succès!');

            return $this->redirectToRoute('app_tournoi_index');
        }

        return $this->render('tournoi/index.html.twig', [
            'form' => $form->createView(),
            'tournois' => $tournoiRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tournoi_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Tournoi $tournoi, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(TournoiType::class, $tournoi);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        $this->addFlash('success', 'Tournoi modifié avec succès!');

        return $this->redirectToRoute('app_tournoi_index');
    }

    return $this->render('tournoi/edit.html.twig', [
        'form' => $form->createView(),
        'tournoi' => $tournoi,
    ]);
}


    #[Route('/{id}/delete', name: 'app_tournoi_delete', methods: ['POST'])]
    public function delete(Request $request, Tournoi $tournoi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tournoi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tournoi);
            $entityManager->flush();

            $this->addFlash('success', 'Tournoi supprimé avec succès!');
        }

        return $this->redirectToRoute('app_tournoi_index');
    }



    #[Route('/tournaments', name: 'app_tournaments')]
    public function tournaments(
        MatchesRepository $matchesRepository,SessionInterface $session,TournoiRepository $tournoiRepository 
    ): Response {
        $userId = $session->get('user_id');
        // Get today's date
        $today = new DateTime('today');
    
        // Fetch all matches
        $matches = $matchesRepository->findAll();
    
        // Filter the matches to find those with a matchDate of today or in the future
        $validMatches = array_filter($matches, function($match) use ($today) {
            return $match->getMatchDate() >= $today;
        });
    
        // Find the match that is "now playing" or coming soon
        $nowPlayingMatch = null;
        foreach ($matches as $match) {
            if ($match->getMatchDate() <= $today) {
                $nowPlayingMatch = $match;
                break;
            }
        }
          // Fetch all tournaments
        $tournois = $tournoiRepository->findAll();

        // Check participation status for each tournament
        $participationStatus = [];
        foreach ($tournois as $tournoi) {
            $maxParticipants = $tournoi->getMaxParticipants();
            $currentParticipants = $tournoi->getParticipationTournois()->count();

            if ($maxParticipants !== null && $currentParticipants >= $maxParticipants) {
                $participationStatus[$tournoi->getId()] = 'full';
            } else {
                $participationStatus[$tournoi->getId()] = 'available';
            }
        }
        // Pass the necessary data to the view
        
        // Pass the necessary data to the view
        return $this->render('esports_all_views/tournaments/tournaments.html.twig', [
            'matches' => $matches,
            'validMatches' => $validMatches,
            'nowPlayingMatch' => $nowPlayingMatch,
            'staticBlock' => $nowPlayingMatch === null,
            'user_id' => $userId,
            'tournois' => $tournois,
            'participationStatus' => $participationStatus,
        ]);
    }
    #[Route('/tournoi/{id}/participate', name: 'app_tournoi_participate', methods: ['POST'])]
    public function participate(
        Request $request,
        Tournoi $tournoi,
        EntityManagerInterface $entityManager,
        SessionInterface $session
    ): Response {
        // Debugging: Check if the method is called
        dump('Participate method called');
    
        // Get the user ID from the session
        $userId = $session->get('user_id');
        dump('Session User ID:', $userId);
    
        // Check if the user is authenticated
        if (!$userId) {
            $this->addFlash('error', 'You must be logged in to participate in a tournament.');
            return $this->redirectToRoute('app_tournaments');
        }
    
        // Get the current user
        $user = $entityManager->getRepository(User::class)->find($userId);
        dump('User:', $user);
    
        // Get the group ID from the form submission
        $groupId = $request->request->get('group_id');
        dump('Group ID from form:', $groupId);
    
        if (!$groupId) {
            $this->addFlash('error', 'Please select a group to participate.');
            return $this->redirectToRoute('app_tournaments');
        }
    
        // Get the group
        $group = $entityManager->getRepository(Group::class)->find($groupId);
        dump('Group:', $group);
    
        // Check if the group exists and the user is the admin
        if (!$group || $group->getAdmin() !== $user) {
            $this->addFlash('error', 'You must be the admin of the selected group to participate in a tournament.');
            return $this->redirectToRoute('app_tournaments');
        }
    
        // Check if the tournament is still open for participation
        $today = new DateTime('today');
        if ($tournoi->getDateFin() < $today) {
            $this->addFlash('error', 'This tournament is no longer open for participation.');
            return $this->redirectToRoute('app_tournaments');
        }
    
        // Check if the tournament has reached its maximum number of participants
        $maxParticipants = $tournoi->getMaxParticipants();
        $currentParticipants = $tournoi->getParticipationTournois()->count();
        if ($maxParticipants !== null && $currentParticipants >= $maxParticipants) {
            $this->addFlash('error', 'This tournament is full.');
            return $this->redirectToRoute('app_tournaments');
        }
    
        // Check if the group is already participating in the tournament
        $isAlreadyParticipating = $tournoi->getParticipationTournois()->exists(function($key, $participation) use ($group) {
            return $participation->getGroup()->getId() === $group->getId();
        });
    
        if ($isAlreadyParticipating) {
            $this->addFlash('error', 'Your group is already participating in this tournament.');
            return $this->redirectToRoute('app_tournaments');
        }
    
        // Create a new participation record
        $participation = new ParticipationTournoi();
        $participation->setTournoi($tournoi);
        $participation->setGroup($group);
        $participation->setEtat(EtatDeParticipationTournoi::PARTICIPANT); // Set the initial state
    
        // Save the participation record
        $entityManager->persist($participation);
        $entityManager->flush();
    
        $this->addFlash('success', 'Your group has successfully participated in the tournament!');
        return $this->redirectToRoute('app_tournaments');
    }
}