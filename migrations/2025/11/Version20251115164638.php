<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251115164638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Notification table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE notification (
              id BINARY(16) NOT NULL,
              type VARCHAR(50) NOT NULL,
              status VARCHAR(50) NOT NULL,
              sender VARCHAR(128) NOT NULL,
              recipient VARCHAR(128) NOT NULL,
              subject VARCHAR(128) NOT NULL,
              message JSON NOT NULL,
              created_at DATETIME NOT NULL,
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE notification');
    }
}
