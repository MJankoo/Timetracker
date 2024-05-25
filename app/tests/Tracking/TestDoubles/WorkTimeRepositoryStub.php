<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tests\Tracking\TestDoubles;

use DateTimeImmutable;
use MJankoo\TimeTracker\Shared\Infrastructure\NextUuidTrait;
use MJankoo\TimeTracker\Tracking\Domain\Entity\WorkTime;
use MJankoo\TimeTracker\Tracking\Domain\Interface\WorkTimeRepositoryInterface;

class WorkTimeRepositoryStub implements WorkTimeRepositoryInterface
{
    use NextUuidTrait;

    /** @var array<array<WorkTime>>  */
    private array $workTimeArray = [];

    public function save(WorkTime $workTime): void
    {
        $this->workTimeArray[$workTime->getEmployeeId()][] = $workTime;
    }

    public function employeeHasTimeEntryForTheDay(string $employeeId, DateTimeImmutable $dateTime): bool
    {
        $employeeWorkTimeEntries = $this->workTimeArray[$employeeId] ?? [];
        /** @var WorkTime $workTime */
        foreach ($employeeWorkTimeEntries as $workTime) {
            if ($dateTime->format('Y-m-d') === $workTime->getStartDate()->format('Y-m-d')) {
                return true;
            }
        }

        return false;
    }

    /** @return array<WorkTime> */
    public function getEntriesFromMonth(string $employeeId, DateTimeImmutable $dateTime): array
    {
        $entries = [];
        $startDate = $dateTime->modify('first day of this month');
        $endDate = $dateTime->modify('last day of this month');

        $employeeWorkTimeEntries = $this->workTimeArray[$employeeId] ?? [];
        /** @var WorkTime $workTime */
        foreach ($employeeWorkTimeEntries as $workTime) {
            if ($startDate <= $workTime->getStartDate() && $workTime->getStartDate() <= $endDate) {
                $entries[] = $workTime;
            }
        }

        return $entries;
    }

    /** @return array<WorkTime> */
    public function getEntriesFromDay(string $employeeId, DateTimeImmutable $dateTime): array
    {
        $employeeWorkTimeEntries = $this->workTimeArray[$employeeId] ?? [];
        foreach ($employeeWorkTimeEntries as $workTime) {
            if ($workTime->getStartDate()->format('Y-m-d') === $dateTime->format('Y-m-d')) {
                return [$workTime];
            }
        }
        return [];
    }

    /**
     * @return array<WorkTime>
     */
    public function getEntriesByEmployeeId(string $employeeId): array
    {
        return $this->workTimeArray[$employeeId] ?? [];
    }
}
