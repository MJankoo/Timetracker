<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Domain\Exception;

use MJankoo\TimeTracker\Shared\Domain\Exception\AbstractDomainException;

final class ThereIsAnotherWorkTimeEntryException extends AbstractDomainException
{
    public static function fromEmployeeId(string $employeeId): self
    {
        $message = sprintf('Employee with id `%s` already has time entry for this date.', $employeeId);
        return new self($message);
    }
}
