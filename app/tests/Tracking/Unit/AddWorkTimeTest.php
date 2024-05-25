<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tests\Tracking\Application\UseCase;

use MJankoo\TimeTracker\Tests\Tracking\TestDoubles\EmployeeRepositoryStub;
use MJankoo\TimeTracker\Tests\Tracking\TestDoubles\WorkTimeRepositoryStub;
use MJankoo\TimeTracker\Tracking\Application\UseCase\AddWorkTime;
use MJankoo\TimeTracker\Tracking\Domain\Entity\Employee;
use MJankoo\TimeTracker\Tracking\Domain\Entity\WorkTime;
use MJankoo\TimeTracker\Tracking\Domain\Exception\EmployeeDoesNotExistException;
use MJankoo\TimeTracker\Tracking\Domain\Exception\ThereIsAnotherWorkTimeEntryException;
use PHPUnit\Framework\TestCase;

class AddWorkTimeTest extends TestCase
{
    public function testSystemDoesNotTrackTimeForNotExistingUser(): void
    {
        $employeeRepository = new EmployeeRepositoryStub();
        $workTimeRepository = new WorkTimeRepositoryStub();
        $sut = new AddWorkTime($employeeRepository, $workTimeRepository);

        // GIVEN
        $start = new \DateTimeImmutable();
        $end = new \DateTimeImmutable();

        // EXPECT
        $this->expectException(EmployeeDoesNotExistException::class);

        // WHEN
        $sut('id', $start, $end);
    }

    public function testSystemDoesNotTrackTimeIfEmployeeAlreadyHasEntryForThisDay(): void
    {
        $employeeRepository = new EmployeeRepositoryStub();
        $workTimeRepository = new WorkTimeRepositoryStub();
        $sut = new AddWorkTime($employeeRepository, $workTimeRepository);

        // GIVEN
        $dateTime = new \DateTimeImmutable();
        $employeeRepository->save(new Employee('employeeId', 'name', 'surname'));
        $workTimeRepository->save(new WorkTime('id', 'employeeId', $dateTime, $dateTime));

        // EXPECT
        $this->expectException(ThereIsAnotherWorkTimeEntryException::class);

        // WHEN
        $sut('employeeId', $dateTime, $dateTime);
    }

    public function testSystemSavesTimeEntry(): void
    {
        $employeeRepository = new EmployeeRepositoryStub();
        $workTimeRepository = new WorkTimeRepositoryStub();
        $sut = new AddWorkTime($employeeRepository, $workTimeRepository);

        // GIVEN
        $start = new \DateTimeImmutable('2024-01-01 00:00:00');
        $end = new \DateTimeImmutable('2024-01-01 08:00:00');
        $employeeRepository->save(new Employee('id', 'name', 'surname'));

        // WHEN
        $sut('id', $start, $end);

        // THEN
        $timeEntries = $workTimeRepository->getEntriesByEmployeeId('id');
        $this->assertSame(1, count($timeEntries));
        $this->assertSame($start, $timeEntries[0]->getStartDateTime());
        $this->assertSame($end, $timeEntries[0]->getEndDateTime());
    }
}
