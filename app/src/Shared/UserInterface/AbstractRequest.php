<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Shared\UserInterface;

use MJankoo\TimeTracker\Shared\UserInterface\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractRequest
{
    final public function __construct(Request $request, ValidatorInterface $validator)
    {
        $this->hydrate($request);
        $this->validate($validator);
    }

    private function hydrate(Request $request): void
    {
        $requestArray = $request->toArray();
        foreach (get_class_vars(get_class($this)) as $attribute => $value) {
            if (isset($requestArray[$attribute])) {
                $this->{$attribute} = $requestArray[$attribute];
            }
        }
    }

    private function validate(ValidatorInterface $validator): void
    {
        $constraintViolations = $validator->validate($this);
        if ($constraintViolations->count() === 0) {
            return;
        }
        throw UnprocessableEntityHttpException::fromConstraintViolationList($constraintViolations);
    }
}
