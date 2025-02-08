<?php

namespace App\Controller\Api;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

class CRUDApiController extends ApiBaseController
{
    private function findEntityById(ServiceEntityRepository $repository, string $id): ?object
    {
        try {
            $uuid = Uuid::fromString($id);
        }
        catch (\Exception $e) {
            return null;
        }
        return $repository->find($uuid);
    }

    private function processOne(ServiceEntityRepository $repository, $id, callable $f): JsonResponse {
        try {
            $entity = $this->findEntityById($repository, $id);

            if (!$entity) {
                throw new NotFoundHttpException();
            }

            return $f($entity);
        } catch (NotFoundHttpException) {
            $this->error('id', new NotFoundHttpException());
            return $this->fail(Response::HTTP_NOT_FOUND);
        } catch (\Exception $exception) {
            $this->error('*', $exception);
            return $this->fail(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function getAll(ServiceEntityRepository $repository): JsonResponse
    {
        $entities = $repository->findAll();
        return $this->success($entities);
    }

    protected function getOne(ServiceEntityRepository $repository, $id): JsonResponse
    {
        return $this->processOne($repository, $id, fn($entity) => $this->success($entity));
    }

    protected function deleteOne(ServiceEntityRepository $repository, $id, $entityManager): JsonResponse
    {
        return $this->processOne($repository, $id, function (object $entity) use ($entityManager) {
            $entityManager->remove($entity);
            $entityManager->flush();
            return $this->success(status: Response::HTTP_NO_CONTENT);
        });
    }

    protected function create(string $class, string $formType, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $group = new $class();
        $form = $this->createForm($formType, $group);
        $form->submit($request->getPayload()->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($group);
            $entityManager->flush();
            return $this->success($group);
        }

        return $this->fail(form: $form);
    }

    protected function updateOne(string $formType, string $id, Request $request, ServiceEntityRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->processOne($repository, $id, function (object $entity) use ($formType, $request, $entityManager) {
            $data = json_decode($request->getContent(), true);
            $form = $this->createForm($formType, $entity);
            $form->submit($data);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($entity);
                $entityManager->flush();
                return $this->success($entity);
            }

            return $this->fail(form: $form);
        });
    }
}
