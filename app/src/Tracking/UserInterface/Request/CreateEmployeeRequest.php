<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\UserInterface\Request;

use MJankoo\TimeTracker\Shared\UserInterface\AbstractRequest;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateEmployeeRequest extends AbstractRequest
{
    #[NotBlank]
    protected string $name;

    #[NotBlank]
    protected string $surname;

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }
}
