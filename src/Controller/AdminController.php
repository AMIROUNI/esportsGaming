<?php

namespace App\Controller;

use App\Repository\TournoiRepository;
use App\Repository\ParticipationTournoiRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'dashboard')]
    public function index(
        TournoiRepository $tr,
        ParticipationTournoiRepository $ptr,
        UserRepository $ur,
        EntityManagerInterface $em
    ): Response {
        // Count the number of gamers, coaches, tournaments, and participations
        $tournois = $tr->count();
        $participations = $ptr->count();
        $users = $ur->findAll();

        $gamers = 0;
        $coaches = 0;

        foreach ($users as $u) {
            if (in_array('ROLE_GAMER', $u->getRoles())) {
                $gamers++;
            }
            if (in_array('ROLE_COACHE', $u->getRoles())) {
                $coaches++;
            }
        }

        // Get participations per group
        $query1 = $em->createQuery('
        SELECT g.nomGroup AS GroupeName, COUNT(p.id) AS participationCount
        FROM App\Entity\ParticipationTournoi p
        JOIN p.group g
        GROUP BY g.nomGroup
    ');
    $participationData = $query1->getResult();

       // Modifier la requête SQL pour MySQL
$sql = "
SELECT YEAR(t.date_fin) AS annee, COUNT(t.id) AS tournoiCount
FROM tournoi t
GROUP BY YEAR(t.date_fin)
";
$stmt = $em->getConnection()->prepare($sql);
$result = $stmt->executeQuery();
$tournoisAnnee = $result->fetchAllAssociative();

        // Render the template with the data
        return $this->render('dashboard/admin.html.twig', [
            'gamerCount' => $gamers,
            'coachCount' => $coaches,
            'tournoiCount' => $tournois,
            'participationCount' => $participations,
            'participationData' => $participationData,
            'tournoisAnnee' => $tournoisAnnee,
        ]);
    }
}