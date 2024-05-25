<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Domain\Interface;

use DateTimeImmutable;
use MJankoo\TimeTracker\Tracking\Domain\Entity\WorkTime;

interface WorkTimeRepositoryInterface
{
    public function save(WorkTime $workTime): void;

    public function employeeHasTimeEntryForTheDay(string $employeeId, DateTimeImmutable $dateTime): bool;

    /** @return array<WorkTime> */
    public function getEntriesFromMonth(string $employeeId, DateTimeImmutable $dateTime): array;

    /** @return array<WorkTime> */
    public function getEntriesFromDay(string $employeeId, DateTimeImmutable $dateTime): array;

    public function getNextId(): string;
}
