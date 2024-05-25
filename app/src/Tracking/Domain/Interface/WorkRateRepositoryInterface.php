<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Domain\Interface;

use DateTimeImmutable;
use MJankoo\TimeTracker\Tracking\Domain\Entity\WorkRate;

interface WorkRateRepositoryInterface
{
    public function getRateForDate(DateTimeImmutable $dateTime): WorkRate;

    public function getNextId(): string;
}
