<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Infrastructure\Doctrine\Orm;

use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use MJankoo\TimeTracker\Shared\Infrastructure\NextUuidTrait;
use MJankoo\TimeTracker\Tracking\Domain\Entity\WorkRate;
use MJankoo\TimeTracker\Tracking\Domain\Exception\ThereIsNoWorkRateForDate;
use MJankoo\TimeTracker\Tracking\Domain\Interface\WorkRateRepositoryInterface;

/**
 * @template-extends ServiceEntityRepository<WorkRate>
 */
class DoctrineOrmWorkRateRepository extends ServiceEntityRepository implements WorkRateRepositoryInterface
{
    use NextUuidTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkRate::class);
    }

    /**
     * @throws ThereIsNoWorkRateForDate
     */
    public function getRateForDate(DateTimeImmutable $dateTime): WorkRate
    {
        $result = $this->createQueryBuilder('wr')
            ->where('wr.date <= :date')
            ->orderBy('wr.date', 'DESC')
            ->setMaxResults(1)
            ->setParameter('date', $dateTime->format('Y-m-d'))
            ->getQuery()
            ->getResult();

        if (!is_array($result) || !$result[0] instanceof WorkRate) {
            throw ThereIsNoWorkRateForDate::fromDate($dateTime);
        }
        return $result[0];
    }
}
