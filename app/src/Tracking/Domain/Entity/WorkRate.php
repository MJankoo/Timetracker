<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Domain\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use MJankoo\TimeTracker\Tracking\Domain\Exception\CannotChangeWorkRateForCurrentMonth;
use MJankoo\TimeTracker\Tracking\Infrastructure\Doctrine\Orm\DoctrineOrmWorkRateRepository;

#[ORM\Entity(repositoryClass: DoctrineOrmWorkRateRepository::class)]
final class WorkRate
{
    #[ORM\Id]
    #[ORM\Column]
    private string $id;

    #[ORM\Column]
    private int $hourlyRate;

    #[ORM\Column]
    private int $hoursRequired;

    #[ORM\Column]
    private int $overtimePercentage;

    #[ORM\Column(type: 'date')]
    private DateTime $date;

    public function __construct(
        string $id,
        int $hourlyRate,
        int $hoursRequired,
        int $overtimePercentage,
        DateTime $date
    ) {
        $this->id = $id;
        $this->hourlyRate = $hourlyRate;
        $this->hoursRequired = $hoursRequired;
        $this->overtimePercentage = $overtimePercentage;
        $this->date = $date;
    }

    public function create(
        string $id,
        int $hourlyRate,
        int $hoursRequired,
        int $overtimePercentage,
        DateTimeImmutable $date
    ): self {
        $dateTime = new DateTimeImmutable();
        if ($date < $dateTime->modify('first day of next month')) {
            throw CannotChangeWorkRateForCurrentMonth::create();
        }

        $date = new DateTime($date->format('Y-m-d'));
        return new self($id, $hourlyRate, $hoursRequired, $overtimePercentage, $date);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getHourlyRate(): int
    {
        return $this->hourlyRate;
    }

    public function getHoursRequired(): int
    {
        return $this->hoursRequired;
    }

    public function getOvertimePercentage(): int
    {
        return $this->overtimePercentage;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }
}
