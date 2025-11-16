<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251116174920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE doctrine_messages');
        $this->addSql('ALTER TABLE notification ADD sender_email VARCHAR(128) DEFAULT NULL, ADD sender_number VARCHAR(128) DEFAULT NULL, ADD recipient_email VARCHAR(128) DEFAULT NULL, ADD recipient_number VARCHAR(128) DEFAULT NULL, DROP sender, DROP recipient');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE doctrine_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, headers LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, queue_name VARCHAR(190) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_97DDDA01FB7336F0 (queue_name), INDEX IDX_97DDDA01E3BD61CE (available_at), INDEX IDX_97DDDA0116BA31DB (delivered_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE notification ADD sender VARCHAR(128) NOT NULL, ADD recipient VARCHAR(128) NOT NULL, DROP sender_email, DROP sender_number, DROP recipient_email, DROP recipient_number');
    }
}
