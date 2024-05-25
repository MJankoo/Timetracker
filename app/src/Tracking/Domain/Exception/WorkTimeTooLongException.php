<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Domain\Exception;

use DateTimeInterface;
use MJankoo\TimeTracker\Shared\Domain\Exception\AbstractDomainException;

final class WorkTimeTooLongException extends AbstractDomainException
{
    public static function fromDateTimePeriod(DateTimeInterface $start, DateTimeInterface $end, int $maxHours): self
    {
        $message = sprintf(
            'Period from %s to %s is longer than %s.',
            $start->format('Y-m-d H:i:s'),
            $end->format('Y-m-d H:i:s'),
            $maxHours,
        );
        return new self($message);
    }
}
