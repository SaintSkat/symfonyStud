<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskFormType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/tasks')]
final class TaskController extends AbstractController
{
    #[Route('/', name: 'task.index', methods: ['GET'])]
    public function getTasks(TaskRepository $repository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'task.new', methods: ['GET', 'POST'])]
    public function createTask(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskFormType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute('task.index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/new.html.twig', [
            'task_group' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'task.edit', methods: ['GET', 'POST'])]
    public function editTask(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskFormType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('task.index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'task.show', methods: ['GET'])]
    public function getTask(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}', name: 'task.delete', methods: ['POST'])]
    public function deleteTask(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
        }
        return $this->redirectToRoute('task.index', status: Response::HTTP_SEE_OTHER);
    }
}
