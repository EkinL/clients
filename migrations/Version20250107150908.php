<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250107150908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE deliverables (id INT AUTO_INCREMENT NOT NULL, id_project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, delivery_date DATE NOT NULL, status VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1A74C3A9B3E79F4B (id_project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE testimonials (id INT AUTO_INCREMENT NOT NULL, id_client_id INT DEFAULT NULL, id_project_id INT DEFAULT NULL, content LONGTEXT NOT NULL, date DATE NOT NULL, INDEX IDX_3831157999DED506 (id_client_id), INDEX IDX_38311579B3E79F4B (id_project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE deliverables ADD CONSTRAINT FK_1A74C3A9B3E79F4B FOREIGN KEY (id_project_id) REFERENCES projects (id)');
        $this->addSql('ALTER TABLE testimonials ADD CONSTRAINT FK_3831157999DED506 FOREIGN KEY (id_client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE testimonials ADD CONSTRAINT FK_38311579B3E79F4B FOREIGN KEY (id_project_id) REFERENCES projects (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE deliverables DROP FOREIGN KEY FK_1A74C3A9B3E79F4B');
        $this->addSql('ALTER TABLE testimonials DROP FOREIGN KEY FK_3831157999DED506');
        $this->addSql('ALTER TABLE testimonials DROP FOREIGN KEY FK_38311579B3E79F4B');
        $this->addSql('DROP TABLE deliverables');
        $this->addSql('DROP TABLE testimonials');
    }
}
