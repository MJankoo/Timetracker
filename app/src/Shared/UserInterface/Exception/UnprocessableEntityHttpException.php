<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Shared\UserInterface\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class UnprocessableEntityHttpException extends HttpException
{
    public static function fromConstraintViolationList(ConstraintViolationListInterface $constraintViolations): self
    {
        $violations = iterator_to_array($constraintViolations);
        $errors = array_map(function (ConstraintViolationInterface $violation) {
            return [
                'path' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }, $violations);

        return new self(Response::HTTP_UNPROCESSABLE_ENTITY, json_encode($errors, JSON_THROW_ON_ERROR));
    }
}
