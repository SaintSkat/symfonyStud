<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

class ApiBaseController extends AbstractController
{
    private array $errors = [];

    public function json(mixed $data = null, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        return parent::json(is_null($data) ? null : ['data' => $data], $status, $headers, $context);
    }

    protected function success(mixed $data = null, int $status = Response::HTTP_OK, array $context = []): JsonResponse
    {
        return $this->json($data, $status, context: $context);
    }

    protected function error($field, \Throwable $exception): void
    {
        $message = match (true) {
            $exception instanceof InvalidArgumentException => 'Invalid value',
            $exception instanceof NotFoundHttpException => 'Not found',
            default => $exception->getMessage(),
        };
        $this->errors[$field][] = $message;
    }

    protected function formErrors(FormInterface $form): void
    {
        foreach ($form->all() as $child) {
            $field = $child->getName();
            foreach ($child->getErrors() as $error) {
                $this->error($field, new \Exception($error->getMessage()));
            }
        }
    }

    protected function fail(int $status = Response::HTTP_BAD_REQUEST, ?FormInterface $form = null): JsonResponse
    {
        if ($form) {
            $this->formErrors($form);
        }
        return $this->json($this->errors, $status);
    }
}
