<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Application\UseCase;

use MJankoo\TimeTracker\Tracking\Domain\Entity\Employee;
use MJankoo\TimeTracker\Tracking\Domain\Interface\EmployeeRepositoryInterface;

final class CreateEmployee
{
    public function __construct(
        private readonly EmployeeRepositoryInterface $employeeRepository
    ) {
    }

    public function __invoke(string $name, string $username): string
    {
        $employeeId = $this->employeeRepository->getNextId();
        $this->employeeRepository->save(new Employee(
            $employeeId,
            $name,
            $username
        ));

        return $employeeId;
    }
}
