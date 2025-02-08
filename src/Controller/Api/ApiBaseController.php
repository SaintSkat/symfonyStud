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

    private function wrap(mixed $data, mixed &$output)
    {
        if (is_array($data)) {
            $output['count'] = count($data);
        }
    }

    public function json(mixed $data = null, int $status = 200, array $headers = [], array $context = [], bool $wrap = true): JsonResponse
    {
        $json = null;
        if (!is_null($data)) {
            $json = [];
            if ($wrap) {
                $this->wrap($data, $json);
            }
            $json['data'] = $data;
        }
        return parent::json($json, $status, $headers, $context);
    }

    protected function success(mixed $data = null, int $status = Response::HTTP_OK): JsonResponse
    {
        return $this->json($data, $status);
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
        return $this->json($this->errors, $status, wrap: false);
    }
}
