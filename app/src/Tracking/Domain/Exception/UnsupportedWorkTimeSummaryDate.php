<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Domain\Exception;

use MJankoo\TimeTracker\Shared\Domain\Exception\AbstractDomainException;

class UnsupportedWorkTimeSummaryDate extends AbstractDomainException
{
    public static function fromDate(string $date): self
    {
        $message = sprintf('Date `%s` is in unsupported format.', $date);
        return new self($message);
    }
}
