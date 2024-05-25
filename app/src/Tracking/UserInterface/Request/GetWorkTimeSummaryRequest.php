<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\UserInterface\Request;

use MJankoo\TimeTracker\Shared\UserInterface\AbstractRequest;
use Symfony\Component\Validator\Constraints\AtLeastOneOf;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class GetWorkTimeSummaryRequest extends AbstractRequest
{
    #[NotBlank]
    protected string $employeeId;

    #[NotBlank]
    #[AtLeastOneOf([
        new Assert\DateTime(format: 'Y-m-d'),
        new Assert\DateTime(format: 'Y-m')
    ])]
    protected string $date;

    public function getEmployeeId(): string
    {
        return $this->employeeId;
    }

    public function getDate(): string
    {
        return $this->date;
    }
}
