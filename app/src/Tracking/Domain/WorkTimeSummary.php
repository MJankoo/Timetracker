<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Domain;

use MJankoo\TimeTracker\Tracking\Domain\Entity\WorkRate;
use MJankoo\TimeTracker\Tracking\Domain\Entity\WorkTime;

class WorkTimeSummary
{
    private function __construct(
        private readonly float $numberOfHours,
        private readonly float $salary,
        private readonly float $standardHours,
        private readonly float $overtimeHours,
    ) {
    }

    /** @param array<WorkTime> $workTimeEntries */
    public static function createFromWorkTimeEntries(
        array $workTimeEntries,
        WorkRate $workRate
    ): self {
        $hours = 0;
        foreach ($workTimeEntries as $workTime) {
            $startTimestamp = $workTime->getStartDateTime()->getTimestamp();
            $endTimestamp = $workTime->getEndDateTime()->getTimestamp();

            $hours += ($endTimestamp - $startTimestamp) / (60 * 60);
        }

        $hours = round($hours, 1);
        $overtimeHours = $hours > $workRate->getHoursRequired() ? $hours - $workRate->getHoursRequired() : 0;
        $standardHours = $hours - $overtimeHours;

        $standardHoursSalary = $standardHours * $workRate->getHourlyRate();
        $overtimeHoursSalary = $overtimeHours * $workRate->getHourlyRate() * ($workRate->getOvertimePercentage() / 100);
        $salary = $standardHoursSalary + $overtimeHoursSalary;

        return new self($hours, $salary, $standardHours, $overtimeHours);
    }

    /** @return array<string, float> */
    public function toArray(): array
    {
        return [
            'numberOfHours' => $this->numberOfHours,
            'salary' => $this->salary,
            'standardHours' => $this->standardHours,
            'overtimeHours' => $this->overtimeHours,
        ];
    }
}
