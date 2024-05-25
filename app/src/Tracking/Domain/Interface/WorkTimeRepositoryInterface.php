<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Domain\Interface;

use DateTimeImmutable;
use MJankoo\TimeTracker\Tracking\Domain\Entity\WorkTime;

interface WorkTimeRepositoryInterface
{
    public function save(WorkTime $workTime): void;

    public function employeeHasTimeEntryForTheDay(string $employeeId, DateTimeImmutable $dateTime): bool;

    public function getNextId(): string;
}
