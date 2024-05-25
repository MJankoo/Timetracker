<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Domain\Interface;

use MJankoo\TimeTracker\Tracking\Domain\Entity\Employee;

interface EmployeeRepositoryInterface
{
    public function save(Employee $employee): void;

    public function employeeExists(string $employeeId): bool;

    public function getNextId(): string;
}
