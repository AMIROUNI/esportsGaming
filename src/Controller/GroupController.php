<?php
namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Form\GroupType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/group')]
final class GroupController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_group_index')]
    public function index(Request $request): Response
    {
        $group = new Group();

        // Get users with ROLE_GAMER
        $users = $this->entityManager->getRepository(User::class)
            ->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%"ROLE_GAMER"%')
            ->getQuery()
            ->getResult();

        // Get all existing groups
        $groups = $this->entityManager->getRepository(Group::class)->findAll();

        // Create the form with the gamers list passed as options
        $form = $this->createForm(GroupType::class, $group, [
            'gamers' => $users,  // Pass the list of gamers to the form
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle image upload if logo is provided
            if ($form->get('logo')->getData()) {
                $this->handleImageUpload($form, $group);
            }

            // Persist the new group and flush to save to database
            $this->entityManager->persist($group);
            $this->entityManager->flush();

            // Flash success message and redirect to group index
            $this->addFlash('success', 'Group created successfully!');
            return $this->redirectToRoute('app_group_index');
        }

        return $this->render('group/index.html.twig', [
            'groupForm' => $form->createView(),
            'groups' => $groups,  // Pass all groups to the template for display
        ]);
    }

    #[Route('/{id}/edit', name: 'app_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Group $group): Response
    {
        // Get users with ROLE_GAMER to display in the edit form
        $users = $this->entityManager->getRepository(User::class)
            ->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%"ROLE_GAMER"%')
            ->getQuery()
            ->getResult();

        $form = $this->createForm(GroupType::class, $group, [
            'gamers' => $users,  // Pass the list of gamers to the form
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle image upload if logo is provided
            if ($form->get('logo')->getData()) {
                $this->handleImageUpload($form, $group);
            }

            // Update the group in the database
            $this->entityManager->flush();

            // Flash success message and redirect to group index
            $this->addFlash('success', 'Group updated successfully!');
            return $this->redirectToRoute('app_group_index');
        }

        return $this->render('group/edit.html.twig', [
            'group' => $group,
            'groupForm' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_group_delete', methods: ['POST'])]
    public function delete(Request $request, Group $group): Response
    {
        // Check CSRF token validity to prevent CSRF attacks
        if ($this->isCsrfTokenValid('delete' . $group->getId(), $request->request->get('_token'))) {
            // Remove the group from the database
            $this->entityManager->remove($group);
            $this->entityManager->flush();

            // Flash success message
            $this->addFlash('success', 'Group deleted successfully!');
        }

        // Redirect back to group index
        return $this->redirectToRoute('app_group_index');
    }

    private function handleImageUpload($form, Group $group): void
    {
        $logoFile = $form->get('logo')->getData();
        if ($logoFile) {
            $newFilename = uniqid() . '.' . $logoFile->guessExtension();
            try {
                // Move the file to the specified directory
                $logoFile->move(
                    $this->getParameter('groupLog_images_directory'),  // Define this in your config/services.yaml
                    $newFilename
                );
                // Set the logo filename in the Group entity
                $group->setLogo($newFilename);
            } catch (\Exception $e) {
                // Handle error, e.g., log it or show a flash message
                $this->addFlash('error', 'Error uploading image: ' . $e->getMessage());
            }
        }
    }
}
