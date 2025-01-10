<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250109130357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE deliverables DROP FOREIGN KEY FK_1A74C3A9166D1F9C');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A499DED506');
        $this->addSql('ALTER TABLE testimonials DROP FOREIGN KEY FK_38311579166D1F9C');
        $this->addSql('ALTER TABLE testimonials DROP FOREIGN KEY FK_3831157999DED506');
        $this->addSql('DROP TABLE deliverables');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE testimonials');
        $this->addSql('ALTER TABLE clients CHANGE phone phone VARCHAR(255) DEFAULT NULL, CHANGE adress address VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE deliverables (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, delivery_date DATE NOT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_1A74C3A9166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE projects (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, budget DOUBLE PRECISION DEFAULT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_5C93B3A419EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE testimonials (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, project_id INT DEFAULT NULL, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date DATE NOT NULL, INDEX IDX_3831157919EB6921 (client_id), INDEX IDX_38311579166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE deliverables ADD CONSTRAINT FK_1A74C3A9166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A499DED506 FOREIGN KEY (client_id) REFERENCES clients (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE testimonials ADD CONSTRAINT FK_38311579166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE testimonials ADD CONSTRAINT FK_3831157999DED506 FOREIGN KEY (client_id) REFERENCES clients (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE clients CHANGE phone phone VARCHAR(100) DEFAULT NULL, CHANGE address adress VARCHAR(255) DEFAULT NULL');
    }
}
