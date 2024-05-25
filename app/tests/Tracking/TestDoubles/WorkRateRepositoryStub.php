<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tests\Tracking\TestDoubles;

use DateTimeImmutable;
use MJankoo\TimeTracker\Shared\Infrastructure\NextUuidTrait;
use MJankoo\TimeTracker\Tracking\Domain\Entity\WorkRate;
use MJankoo\TimeTracker\Tracking\Domain\Interface\WorkRateRepositoryInterface;

class WorkRateRepositoryStub implements WorkRateRepositoryInterface
{
    use NextUuidTrait;

    public function __construct(
        private readonly int $hourlyRate,
        private readonly int $hoursRequired,
        private readonly int $overtimePercentage
    ) {

    }

    public function getRateForDate(DateTimeImmutable $dateTime): WorkRate
    {
        return new WorkRate(
            'id',
            $this->hourlyRate,
            $this->hoursRequired,
            $this->overtimePercentage,
            new \DateTime('1970-01-01')
        );
    }
}
