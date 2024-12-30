<?php

namespace App\Controller;

use App\Entity\Lp;
use App\Form\LpType;
use App\Repository\LpRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/lp')]
final class LpController extends AbstractController
{
   

    #[Route('', name: 'app_lp_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,LpRepository $lpRepository): Response
    {
        $lp = new Lp();
        $form = $this->createForm(LpType::class, $lp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lp);
            $entityManager->flush();

            return $this->redirectToRoute('app_lp_new', [], Response::HTTP_SEE_OTHER);
        }

        $lps=$lpRepository->findAll();
        return $this->render('lp/new.html.twig', [
            'lp' => $lp,
            'form' => $form,
            'lps' => $lps,
        ]);
    }

    #[Route('/{id}', name: 'app_lp_show', methods: ['GET'])]
    public function show(Lp $lp): Response
    {
        return $this->render('lp/show.html.twig', [
            'lp' => $lp,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lp_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lp $lp, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LpType::class, $lp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_lp_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lp/edit.html.twig', [
            'lp' => $lp,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lp_delete', methods: ['POST'])]
    public function delete(Request $request, Lp $lp, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lp->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($lp);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_lp_new', [], Response::HTTP_SEE_OTHER);
    }
}
