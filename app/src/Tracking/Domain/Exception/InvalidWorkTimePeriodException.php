<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Domain\Exception;

use DateTimeInterface;
use MJankoo\TimeTracker\Shared\Domain\Exception\AbstractDomainException;

final class InvalidWorkTimePeriodException extends AbstractDomainException
{
    public static function fromDateTimePeriod(DateTimeInterface $start, DateTimeInterface $end): self
    {
        $message = sprintf(
            'End date can not be before the start date (Start: %s, End: %s).',
            $start->format('Y-m-d H:i:s'),
            $end->format('Y-m-d H:i:s')
        );
        return new self($message);
    }
}
