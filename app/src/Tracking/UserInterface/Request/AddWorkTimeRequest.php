<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\UserInterface\Request;

use MJankoo\TimeTracker\Shared\UserInterface\AbstractRequest;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddWorkTimeRequest extends AbstractRequest
{
    #[NotBlank]
    protected string $employeeId;

    #[NotBlank]
    #[DateTime(format: 'Y-m-d H:i:s')]
    protected string $startDateTime;

    #[NotBlank]
    #[DateTime(format: 'Y-m-d H:i:s')]
    protected string $endDateTime;

    public function getEmployeeId(): string
    {
        return $this->employeeId;
    }

    public function getStartDateTime(): string
    {
        return $this->startDateTime;
    }

    public function getEndDateTime(): string
    {
        return $this->endDateTime;
    }
}
