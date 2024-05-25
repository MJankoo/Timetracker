<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tests\Tracking\TestDoubles;

use MJankoo\TimeTracker\Tracking\Domain\Entity\Employee;
use MJankoo\TimeTracker\Tracking\Domain\Interface\EmployeeRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class EmployeeRepositoryStub implements EmployeeRepositoryInterface
{
    /** @var array<Employee>  */
    private array $employees = [];

    public function save(Employee $employee): void
    {
        $this->employees[$employee->getId()] = $employee;
    }

    public function getEmployee(string $id): ?Employee
    {
        return $this->employees[$id] ?? null;
    }

    public function getNextId(): string
    {
        return (string) Uuid::v4();
    }
}
