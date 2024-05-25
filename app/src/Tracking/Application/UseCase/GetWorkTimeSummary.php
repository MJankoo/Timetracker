<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Application\UseCase;

use DateTimeImmutable;
use MJankoo\TimeTracker\Tracking\Domain\Entity\WorkTime;
use MJankoo\TimeTracker\Tracking\Domain\Exception\EmployeeDoesNotExistException;
use MJankoo\TimeTracker\Tracking\Domain\Exception\UnsupportedWorkTimeSummaryDate;
use MJankoo\TimeTracker\Tracking\Domain\Interface\EmployeeRepositoryInterface;
use MJankoo\TimeTracker\Tracking\Domain\Interface\WorkRateRepositoryInterface;
use MJankoo\TimeTracker\Tracking\Domain\Interface\WorkTimeRepositoryInterface;
use MJankoo\TimeTracker\Tracking\Domain\WorkTimeSummary;

class GetWorkTimeSummary
{
    private const PATTERN_YYYY_MM = '/^\d{4}-\d{2}$/';

    private const PATTERN_YYYY_MM_DD = '/^\d{4}-\d{2}-\d{2}$/';

    public function __construct(
        private readonly EmployeeRepositoryInterface $employeeRepository,
        private readonly WorkTimeRepositoryInterface $workTimeRepository,
        private readonly WorkRateRepositoryInterface $workRateRepository,
    ) {
    }

    public function __invoke(string $employeeId, string $date): WorkTimeSummary
    {
        if (!$this->employeeRepository->employeeExists($employeeId)) {
            throw EmployeeDoesNotExistException::fromEmployeeId($employeeId);
        }

        $timeEntries = $this->getWorkTimeEntriesFromDate($employeeId, $date);
        $workRate = $this->workRateRepository->getRateForDate(new DateTimeImmutable($date));

        return WorkTimeSummary::createFromWorkTimeEntries($timeEntries, $workRate);
    }

    /** @return array<WorkTime> */
    private function getWorkTimeEntriesFromDate(string $employeeId, string $date): array
    {
        $dateTime = new DateTimeImmutable($date);
        if (preg_match(self::PATTERN_YYYY_MM, $date)) {
            return $this->workTimeRepository->getEntriesFromMonth($employeeId, $dateTime);
        }

        if (preg_match(self::PATTERN_YYYY_MM_DD, $date)) {
            return $this->workTimeRepository->getEntriesFromDay($employeeId, $dateTime);
        }

        throw UnsupportedWorkTimeSummaryDate::fromDate($date);
    }
}
