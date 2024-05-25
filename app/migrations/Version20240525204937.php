<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240525204937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $createTableSql = <<<SQL
            CREATE TABLE work_rate (
                id VARCHAR(255) NOT NULL,
                hourly_rate INT NOT NULL,
                hours_required INT NOT NULL,
                overtime_percentage INT NOT NULL,
                date DATE NOT NULL, PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
        SQL;
        $this->addSql($createTableSql);

        $addRowSql = <<<SQL
            INSERT INTO 
                work_rate (id, hourly_rate, hours_required, overtime_percentage, date) 
            VALUES ('9600c08e-7158-41fa-8a4a-72a12908ea0f', 20, 40, 200, '1970-01-01');
        SQL;
        $this->addSql($addRowSql);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE work_rate');
    }
}
