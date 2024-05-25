<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240525195613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add work_time table';
    }

    public function up(Schema $schema): void
    {
        $sql = <<<SQL
            CREATE TABLE work_time (
                id VARCHAR(255) NOT NULL,
                employee_id VARCHAR(255) NOT NULL,
                start_date_time DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                end_date_time DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                start_date DATE NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                PRIMARY KEY(id),
                FOREIGN KEY (employee_id) REFERENCES employee(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL;

        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE work_time');
    }
}
