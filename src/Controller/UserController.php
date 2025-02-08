<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskFormType;
use App\Form\UserTaskFormType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class UserController extends AbstractController
{
    #[Route('/', name: 'profile.index', methods: ['GET'])]
    public function index(TaskRepository $repository): Response
    {
        return $this->render('profile/index.html.twig', [
            'tasks' => $repository->findBy(['user' => $this->getUser()]),
        ]);
    }

    #[Route('/tasks/new', name: 'profile.task.new', methods: ['GET', 'POST'])]
    public function createTask(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskFormType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());
            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute('profile.index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/new.html.twig', [
            'task_group' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/tasks/{id}/edit', name: 'profile.task.edit', methods: ['GET', 'POST'])]
    public function editTask(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($task->getUser()->getUserIdentifier() !== $this->getUser()->getUserIdentifier()) {
            throw $this->createAccessDeniedException('Invalid ownership');
        }

        $form = $this->createForm(TaskFormType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('profile.index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/tasks/{id}', name: 'profile.task.show', methods: ['GET'])]
    public function getTask(Task $task): Response
    {
        return $this->render('profile/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}', name: 'profile.task.delete', methods: ['POST'])]
    public function deleteTask(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
        }
        return $this->redirectToRoute('profile.index', status: Response::HTTP_SEE_OTHER);
    }
}
