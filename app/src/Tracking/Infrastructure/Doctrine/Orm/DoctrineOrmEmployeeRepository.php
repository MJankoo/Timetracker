<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Infrastructure\Doctrine\Orm;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use MJankoo\TimeTracker\Tracking\Domain\Entity\Employee;
use MJankoo\TimeTracker\Tracking\Domain\Interface\EmployeeRepositoryInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @template-extends ServiceEntityRepository<Employee>
 */
final class DoctrineOrmEmployeeRepository extends ServiceEntityRepository implements EmployeeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function save(Employee $employee): void
    {
        $this->getEntityManager()->persist($employee);
        $this->getEntityManager()->flush();
    }

    public function getNextId(): string
    {
        return (string) Uuid::v4();
    }
}
