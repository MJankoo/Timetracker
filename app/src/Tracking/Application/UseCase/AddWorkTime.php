<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Application\UseCase;

use DateTimeImmutable;
use MJankoo\TimeTracker\Tracking\Domain\Entity\WorkTime;
use MJankoo\TimeTracker\Tracking\Domain\Exception\EmployeeDoesNotExistException;
use MJankoo\TimeTracker\Tracking\Domain\Exception\ThereIsAnotherWorkTimeEntryException;
use MJankoo\TimeTracker\Tracking\Domain\Interface\EmployeeRepositoryInterface;
use MJankoo\TimeTracker\Tracking\Domain\Interface\WorkTimeRepositoryInterface;

final class AddWorkTime
{
    public function __construct(
        private readonly EmployeeRepositoryInterface $employeeRepository,
        private readonly WorkTimeRepositoryInterface $workTimeEntryRepository,
    ) {
    }

    /**
     * @throws EmployeeDoesNotExistException
     * @throws ThereIsAnotherWorkTimeEntryException
     */
    public function __invoke(string $employeeId, DateTimeImmutable $startDateTime, DateTimeImmutable $endDateTime): void
    {
        if (!$this->employeeRepository->employeeExists($employeeId)) {
            throw EmployeeDoesNotExistException::fromEmployeeId($employeeId);
        }

        if ($this->workTimeEntryRepository->employeeHasTimeEntryForTheDay($employeeId, $startDateTime)) {
            throw ThereIsAnotherWorkTimeEntryException::fromEmployeeId($employeeId);
        }

        $timeEntryId = $this->workTimeEntryRepository->getNextId();
        $timeEntry = WorkTime::create($timeEntryId, $employeeId, $startDateTime, $endDateTime);
        $this->workTimeEntryRepository->save($timeEntry);
    }
}
