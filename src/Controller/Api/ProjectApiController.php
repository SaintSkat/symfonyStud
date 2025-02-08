<?php

namespace App\Controller\Api;

use App\Entity\Project;
use App\Form\ProjectFormType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/projects')]
final class ProjectApiController extends CRUDApiController
{
    public function __construct()
    {
        $this->contextGroup = 'project';
    }

    #[Route('/', methods: ['GET'])]
    public function getProjects(ProjectRepository $repository): JsonResponse
    {
        return $this->getAll($repository);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getProject(string $id, ProjectRepository $repository): JsonResponse
    {
        return $this->getOne($repository, $id);
    }

    #[Route('/', methods: ['POST'], format: 'json')]
    public function createProject(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->create(Project::class, ProjectFormType::class, $request, $entityManager);
    }

    #[Route('/{id}', methods: ['DELETE'], format: 'json')]
    public function deleteProject(string $id, ProjectRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->deleteOne($repository, $id, $entityManager);
    }

    #[Route('/{id}', methods: ['PATCH'], format: 'json')]
    public function editProject(string $id, Request $request, ProjectRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->updateOne(ProjectFormType::class, $id, $request, $repository, $entityManager);
    }
}
