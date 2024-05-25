<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tests\Tracking\Application\UseCase;

use DateTimeImmutable;
use MJankoo\TimeTracker\Tracking\Domain\Entity\WorkTime;

use MJankoo\TimeTracker\Tracking\Domain\Exception\InvalidWorkTimePeriodException;
use MJankoo\TimeTracker\Tracking\Domain\Exception\WorkTimeTooLongException;
use PHPUnit\Framework\TestCase;

class WorkTimeTest extends TestCase
{
    public function testSystemBlocksTimeEntryCreationIfItsLongerThan12Hours(): void
    {
        // GIVEN
        $start = new DateTimeImmutable('2024-01-01 00:00:00');
        $end = $start->modify('+ 12 hours 1 second');

        // EXPECT
        $this->expectException(WorkTimeTooLongException::class);

        // WHEN
        WorkTime::create('id', 'employeeId', $start, $end);
    }

    public function testSystemBlocksTimeEntryCreationIfEndDateIsSmallerThanStartDate(): void
    {
        // GIVEN
        $start = new DateTimeImmutable('2024-01-01 00:00:00');
        $end = $start->modify('- 1 second');

        // EXPECT
        $this->expectException(InvalidWorkTimePeriodException::class);

        // WHEN
        WorkTime::create('id', 'employeeId', $start, $end);
    }
}
