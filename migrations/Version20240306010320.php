<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306010320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donations_list ADD project_id INT NOT NULL');
        $this->addSql('ALTER TABLE donations_list ADD CONSTRAINT FK_B22917B3166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
        $this->addSql('CREATE INDEX IDX_B22917B3166D1F9C ON donations_list (project_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donations_list DROP FOREIGN KEY FK_B22917B3166D1F9C');
        $this->addSql('DROP INDEX IDX_B22917B3166D1F9C ON donations_list');
        $this->addSql('ALTER TABLE donations_list DROP project_id');
    }
}
