<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306111124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donations_list CHANGE project_id project INT NOT NULL');
        $this->addSql('ALTER TABLE donations_list ADD CONSTRAINT FK_B22917B32FB3D0EE FOREIGN KEY (project) REFERENCES projects (id)');
        $this->addSql('CREATE INDEX IDX_B22917B32FB3D0EE ON donations_list (project)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donations_list DROP FOREIGN KEY FK_B22917B32FB3D0EE');
        $this->addSql('DROP INDEX IDX_B22917B32FB3D0EE ON donations_list');
        $this->addSql('ALTER TABLE donations_list CHANGE project project_id INT NOT NULL');
    }
}
