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

    /** @return array<WorkTime> */
    public function getEntriesFromMonth(string $employeeId, DateTimeImmutable $dateTime): array
    {
        $startDate = $dateTime->modify('first day of this month');
        $endDate =  $dateTime->modify('last day of this month');

        /** @var array<WorkTime> $result */
        $result = $this->createQueryBuilder('wt')
            ->where('wt.startDate BETWEEN :start AND :end')
            ->andWhere('wt.employeeId = :employeeId')
            ->setParameter('start', $startDate->format('Y-m-d'))
            ->setParameter('end', $endDate->format('Y-m-d'))
            ->setParameter('employeeId', $employeeId)
            ->getQuery()
            ->getResult();
        return $result;
    }

    /** @return array<WorkTime> */
    public function getEntriesFromDay(string $employeeId, DateTimeImmutable $dateTime): array
    {
        return $this->findBy([
            'startDate' => $dateTime,
            'employeeId' => $employeeId
        ]);
    }
}
