<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207091352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clients ADD generate_with_ai TINYINT(1) DEFAULT NULL, ADD prompt VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP generate_with_ai, DROP prompt');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clients DROP generate_with_ai, DROP prompt');
        $this->addSql('ALTER TABLE user ADD generate_with_ai TINYINT(1) DEFAULT NULL, ADD prompt VARCHAR(255) DEFAULT NULL');
    }
}
