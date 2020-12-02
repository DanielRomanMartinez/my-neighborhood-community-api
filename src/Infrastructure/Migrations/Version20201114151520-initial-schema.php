<?php

declare(strict_types=1);

namespace App\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201114151520 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Initial Schema';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE customers (id BINARY(16) NOT NULL, readable_id VARCHAR(36) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL, first_name VARCHAR(50) DEFAULT NULL, middle_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, last_login_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_62534E214479C613 (readable_id), UNIQUE INDEX email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE customers');
    }
}
