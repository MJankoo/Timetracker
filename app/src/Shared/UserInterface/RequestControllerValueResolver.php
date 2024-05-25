<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Shared\UserInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestControllerValueResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator
    ) {
    }

    /**
     * @return iterable<AbstractRequest>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        if (!$argumentType || !is_subclass_of($argumentType, AbstractRequest::class)) {
            return [];
        }
        return [new $argumentType($request, $this->validator)];
    }
}
