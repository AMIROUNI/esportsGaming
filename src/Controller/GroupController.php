<?php
namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Form\GroupType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
    #[Route('/',"app_group")]
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

    // Pass the users to the form as an option
    $form = $this->createForm(GroupType::class, $group, [
        'gamers' => $users,  // Pass the list of users to the form
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        if ($form->get('logo')->getData()) {
            $this->handleImageUpload($form, $group);
        }
        $this->entityManager->persist($group);
        $this->entityManager->flush();

        $this->addFlash('success', 'Group created successfully!');
        return $this->redirectToRoute('app_group_index');
    }

    return $this->render('group/index.html.twig', [
        'groupForm' => $form->createView(),
    ]);
}

    #[Route('/{id}/edit', name: 'app_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Group $group): Response
    {
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('logo')->getData()) {
                $this->handleImageUpload($form, $group);
            }
            $this->entityManager->flush();  // Using EntityManager to flush changes

            $this->addFlash('success', 'Group updated successfully!');
            return $this->redirectToRoute('app_group_index');
        }

        return $this->render('group/edit.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_group_delete', methods: ['POST'])]
    public function delete(Request $request, Group $group): Response
    {
        if ($this->isCsrfTokenValid('delete' . $group->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($group);  // Using EntityManager to remove the group
            $this->entityManager->flush();         // Flushing to save changes to the database
            $this->addFlash('success', 'Group deleted successfully!');
        }

        return $this->redirectToRoute('app_group_index');
    }

    private function handleImageUpload($form, Group $group): void
    {
        $logoFile = $form->get('logo')->getData();
        if ($logoFile) {
            // Handle image upload logic here
            $newFilename = uniqid() . '.' . $logoFile->guessExtension();

            try {
                $logoFile->move(
                    $this->getParameter('images_directory'), // Define your directory in config/services.yaml
                    $newFilename
                );
                $group->setLogo($newFilename); // Set the logo filename to the entity
            } catch (Exception $e) {
                
            }
        }
    }
}
