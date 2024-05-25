<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Domain\Exception;

use MJankoo\TimeTracker\Shared\Domain\Exception\AbstractDomainException;

class CannotChangeWorkRateForCurrentMonth extends AbstractDomainException
{
    public static function create(): self
    {
        return new self('Cannot change work rate for current month.');
    }
}
