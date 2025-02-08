<?php

namespace App\Controller\Api;

use App\Entity\Task;
use App\Form\TaskCreatingFormType;
use App\Form\TaskFormType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/tasks')]
final class TaskApiController extends CRUDApiController
{
    public function __construct()
    {
        $this->contextGroup = 'task';
    }

    #[Route('/', methods: ['GET'])]
    public function getTasks(TaskRepository $repository): JsonResponse
    {
        return $this->getAll($repository);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getTask(string $id, TaskRepository $repository): JsonResponse
    {
        return $this->getOne($repository, $id);
    }

    #[Route('/', methods: ['POST'], format: 'json')]
    public function createTask(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->create(Task::class, TaskCreatingFormType::class, $request, $entityManager);
    }

    #[Route('/{id}', methods: ['DELETE'], format: 'json')]
    public function deleteTask(string $id, TaskRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->deleteOne($repository, $id, $entityManager);
    }

    #[Route('/{id}', methods: ['PATCH'], format: 'json')]
    public function editTask(string $id, Request $request, TaskRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->updateOne(TaskFormType::class, $id, $request, $repository, $entityManager);
    }
}
