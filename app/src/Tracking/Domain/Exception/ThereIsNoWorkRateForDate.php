<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Domain\Exception;

use DateTimeImmutable;
use MJankoo\TimeTracker\Shared\Domain\Exception\AbstractDomainException;

final class ThereIsNoWorkRateForDate extends AbstractDomainException
{
    public static function fromDate(DateTimeImmutable $date): self
    {
        $message = sprintf('There is no work rate for date `%s`', $date->format('Y-m-d'));
        return new self($message);
    }
}
