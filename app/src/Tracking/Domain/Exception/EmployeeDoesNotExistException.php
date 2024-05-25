<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Domain\Exception;

use MJankoo\TimeTracker\Shared\Domain\Exception\AbstractDomainException;

final class EmployeeDoesNotExistException extends AbstractDomainException
{
    public static function fromEmployeeId(string $employeeId): self
    {
        $message = sprintf('Employee with id `%s` does not exist.', $employeeId);
        return new self($message);
    }
}
