<?php

declare(strict_types=1);

namespace MJankoo\TimeTracker\Tracking\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use MJankoo\TimeTracker\Tracking\Infrastructure\Doctrine\Orm\DoctrineOrmEmployeeRepository;

#[ORM\Entity(repositoryClass: DoctrineOrmEmployeeRepository::class)]
final class Employee
{
    #[ORM\Id]
    #[ORM\Column]
    private string $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $surname;

    public function __construct(string $id, string $name, string $surname)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }
}
