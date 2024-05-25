<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tests\Tracking\Application\UseCase;

use MJankoo\TimeTracker\Tests\Tracking\TestDoubles\EmployeeRepositoryStub;
use MJankoo\TimeTracker\Tests\Tracking\TestDoubles\WorkRateRepositoryStub;
use MJankoo\TimeTracker\Tests\Tracking\TestDoubles\WorkTimeRepositoryStub;
use MJankoo\TimeTracker\Tracking\Application\UseCase\GetWorkTimeSummary;
use MJankoo\TimeTracker\Tracking\Domain\Entity\Employee;
use MJankoo\TimeTracker\Tracking\Domain\Entity\WorkRate;
use MJankoo\TimeTracker\Tracking\Domain\Entity\WorkTime;
use MJankoo\TimeTracker\Tracking\Domain\WorkTimeSummary;
use PHPUnit\Framework\TestCase;

class GetWorkTimeSummaryTest extends TestCase
{
    public function testSystemReturnsSummaryFromOneDay(): void
    {
        // GIVEN
        $entries = [$this->createWorkTime('2024-01-02 00:00:00', '2024-01-02 01:00:00')];
        $workRate = new WorkRate('id', 20, 40, 200, new \DateTime('1970-01-01'));

        // WHEN
        $summary = WorkTimeSummary::createFromWorkTimeEntries($entries, $workRate)->toArray();

        // THEN
        $this->assertSame(1.0, $summary['numberOfHours']);
        $this->assertSame(1.0, $summary['standardHours']);
        $this->assertSame(0.0, $summary['overtimeHours']);
        $this->assertSame(20.0, $summary['salary']);
    }

    public function testSystemReturnsSummaryFromMonth(): void
    {
        // GIVEN
        $entries = [
            $this->createWorkTime('2024-01-01 00:00:00', '2024-01-01 01:00:00'),
            $this->createWorkTime('2024-01-02 00:00:00', '2024-01-02 01:00:00'),
            $this->createWorkTime('2024-01-03 00:00:00', '2024-01-03 01:00:00')
        ];
        $workRate = new WorkRate('id', 20, 40, 200, new \DateTime('1970-01-01'));

        // WHEN
        $summary = WorkTimeSummary::createFromWorkTimeEntries($entries, $workRate)->toArray();

        // THEN
        $this->assertSame(3.0, $summary['numberOfHours']);
        $this->assertSame(3.0, $summary['standardHours']);
        $this->assertSame(0.0, $summary['overtimeHours']);
        $this->assertSame(60.0, $summary['salary']);
    }

    public function testSystemCalculatesOvertime(): void
    {
        // GIVEN
        $entries = [
            $this->createWorkTime('2024-01-01 00:00:00', '2024-01-01 12:00:00'),
            $this->createWorkTime('2024-01-02 00:00:00', '2024-01-02 12:00:00'),
            $this->createWorkTime('2024-01-03 00:00:00', '2024-01-03 12:00:00')
        ];
        $workRate = new WorkRate('id', 20, 20, 200, new \DateTime('1970-01-01'));

        // WHEN
        $summary = WorkTimeSummary::createFromWorkTimeEntries($entries, $workRate)->toArray();

        // THEN
        $this->assertSame(36.0, $summary['numberOfHours']);
        $this->assertSame(20.0, $summary['standardHours']);
        $this->assertSame(16.0, $summary['overtimeHours']);
        $this->assertSame(1040.0, $summary['salary']);
    }

    private function createWorkTime(string $start, string $end): WorkTime
    {
        return new WorkTime('id', 'employeeId', new \DateTimeImmutable($start), new \DateTimeImmutable($end));
    }
}
