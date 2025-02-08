<?php

namespace App\Controller;

use App\Entity\ProjectGroup;
use App\Form\ProjectGroupFormType;
use App\Repository\ProjectGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/groups')]
final class ProjectGroupController extends AbstractController
{
    #[Route('/', name: 'project_group.index', methods: ['GET'])]
    public function getProjectGroups(ProjectGroupRepository $repository): Response
    {
        return $this->render('project_group/index.html.twig', [
            'project_groups' => $repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'project_group.new', methods: ['GET', 'POST'])]
    public function createProjectGroup(Request $request, EntityManagerInterface $entityManager): Response
    {
        $group = new ProjectGroup();
        $form = $this->createForm(ProjectGroupFormType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($group);
            $entityManager->flush();
            return $this->redirectToRoute('project_group.index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('project_group/new.html.twig', [
            'project_group' => $group,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'project_group.edit', methods: ['GET', 'POST'])]
    public function editProjectGroup(Request $request, ProjectGroup $group, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectGroupFormType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('project_group.index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('project_group/edit.html.twig', [
            'project_group' => $group,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'project_group.show', methods: ['GET'])]
    public function getProjectGroup(ProjectGroup $group): Response
    {
        return $this->render('project_group/show.html.twig', [
            'project_group' => $group,
        ]);
    }

    #[Route('/{id}', name: 'project_group.delete', methods: ['POST'])]
    public function deleteProjectGroup(Request $request, ProjectGroup $group, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $group->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($group);
            $entityManager->flush();
        }
        return $this->redirectToRoute('project_group.index', status: Response::HTTP_SEE_OTHER);
    }
}
