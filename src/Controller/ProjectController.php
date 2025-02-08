<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectFormType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/projects')]
final class ProjectController extends AbstractController
{
    #[Route('/', name: 'project.index', methods: ['GET'])]
    public function getProjects(ProjectRepository $repository): Response
    {
        return $this->render('project/index.html.twig', [
            'projects' => $repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'project.new', methods: ['GET', 'POST'])]
    public function createProject(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectFormType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();
            return $this->redirectToRoute('project.index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/new.html.twig', [
            'project_group' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'project.edit', methods: ['GET', 'POST'])]
    public function editProject(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectFormType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('project.index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'project.show', methods: ['GET'])]
    public function getProject(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/{id}', name: 'project.delete', methods: ['POST'])]
    public function deleteProject(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $project->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($project);
            $entityManager->flush();
        }
        return $this->redirectToRoute('project.index', status: Response::HTTP_SEE_OTHER);
    }
}
