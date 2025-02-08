<?php

namespace App\Controller\Api;

use App\Entity\ProjectGroup;
use App\Form\ProjectGroupFormType;
use App\Repository\ProjectGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/groups')]
final class ProjectGroupApiController extends CRUDApiController
{
    #[Route('/', methods: ['GET'])]
    public function getProjectGroups(ProjectGroupRepository $repository): JsonResponse
    {
        return $this->getAll($repository);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getProjectGroup(string $id, ProjectGroupRepository $repository): JsonResponse
    {
        return $this->getOne($repository, $id);
    }

    #[Route('/', methods: ['POST'], format: 'json')]
    public function createProjectGroup(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->create(ProjectGroup::class, ProjectGroupFormType::class, $request, $entityManager);
    }

    #[Route('/{id}', methods: ['DELETE'], format: 'json')]
    public function deleteProjectGroup(string $id, ProjectGroupRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->deleteOne($repository, $id, $entityManager);
    }

    #[Route('/{id}', methods: ['PATCH'], format: 'json')]
    public function editProjectGroup(string $id, Request $request, ProjectGroupRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->updateOne(ProjectGroupFormType::class, $id, $request, $repository, $entityManager);
    }
}
