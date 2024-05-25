<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Shared\Infrastructure;

use Symfony\Component\Uid\Uuid;

trait NextUuidTrait
{
    public function getNextId(): string
    {
        return (string) Uuid::v4();
    }
}
