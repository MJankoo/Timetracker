<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Infrastructure\Doctrine\Orm;

use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use MJankoo\TimeTracker\Shared\Infrastructure\NextUuidTrait;
use MJankoo\TimeTracker\Tracking\Domain\Entity\WorkTime;
use MJankoo\TimeTracker\Tracking\Domain\Interface\WorkTimeRepositoryInterface;

/**
 * @template-extends ServiceEntityRepository<WorkTime>
 */
class DoctrineOrmWorkTimeRepository extends ServiceEntityRepository implements WorkTimeRepositoryInterface
{
    use NextUuidTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkTime::class);
    }

    public function save(WorkTime $workTime): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($workTime);
        $entityManager->flush();
    }

    public function employeeHasTimeEntryForTheDay(string $employeeId, DateTimeImmutable $dateTime): bool
    {
        $workTime = $this->findBy([
            'startDate' => $dateTime,
            'employeeId' => $employeeId
        ]);
        return count($workTime) !== 0;
    }
}
