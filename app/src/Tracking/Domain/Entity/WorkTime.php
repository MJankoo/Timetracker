<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Domain\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use MJankoo\TimeTracker\Tracking\Domain\Exception\InvalidWorkTimePeriodException;
use MJankoo\TimeTracker\Tracking\Domain\Exception\WorkTimeTooLongException;
use MJankoo\TimeTracker\Tracking\Infrastructure\Doctrine\Orm\DoctrineOrmWorkTimeRepository;

#[ORM\Entity(repositoryClass: DoctrineOrmWorkTimeRepository::class)]
final class WorkTime
{
    #[ORM\Id]
    #[ORM\Column]
    private string $id;

    #[ORM\Column]
    private string $employeeId;

    #[ORM\Column]
    private DateTimeImmutable $startDateTime;

    #[ORM\Column]
    private DateTimeImmutable $endDateTime;

    #[ORM\Column(type: 'date')]
    private DateTime $startDate;

    public function __construct(
        string $id,
        string $employeeId,
        DateTimeImmutable $startDateTime,
        DateTimeImmutable $endDateTime
    ) {
        $this->id = $id;
        $this->employeeId = $employeeId;
        $this->startDateTime = $startDateTime;
        $this->endDateTime = $endDateTime;
        $this->startDate = new DateTime($startDateTime->format('Y-m-d'));
    }

    public static function create(
        string $id,
        string $employeeId,
        DateTimeImmutable $startDateTime,
        DateTimeImmutable $endDateTime
    ): self {
        if ($endDateTime <= $startDateTime) {
            throw InvalidWorkTimePeriodException::fromDateTimePeriod($startDateTime, $endDateTime);
        }

        if ($endDateTime->modify('-12 hours') >= $startDateTime) {
            throw WorkTimeTooLongException::fromDateTimePeriod($startDateTime, $endDateTime, 12);
        }

        return new self($id, $employeeId, $startDateTime, $endDateTime);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmployeeId(): string
    {
        return $this->employeeId;
    }

    public function getStartDateTime(): DateTimeImmutable
    {
        return $this->startDateTime;
    }

    public function getEndDateTime(): DateTimeImmutable
    {
        return $this->endDateTime;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }
}
