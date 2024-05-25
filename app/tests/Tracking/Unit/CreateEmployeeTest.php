<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tests\Tracking\Application\UseCase;

use MJankoo\TimeTracker\Tests\Tracking\TestDoubles\EmployeeRepositoryStub;
use MJankoo\TimeTracker\Tracking\Application\UseCase\CreateEmployee;
use MJankoo\TimeTracker\Tracking\Domain\Entity\Employee;
use PHPUnit\Framework\TestCase;

class CreateEmployeeTest extends TestCase
{
    public function testSystemCreatesEmployee(): void
    {
        $employeeRepository = new EmployeeRepositoryStub();
        $sut = new CreateEmployee($employeeRepository);

        // GIVEN
        $name = 'test-name';
        $surname = 'test-surname';

        // WHEN
        $employeeId = ($sut)($name, $surname);

        // THEN
        $employee = $employeeRepository->getEmployee($employeeId);
        $this->assertInstanceOf(Employee::class, $employee);
        $this->assertSame('test-name', $employee->getName());
        $this->assertSame('test-surname', $employee->getSurname());
    }
}
