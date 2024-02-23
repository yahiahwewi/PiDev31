<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240222204813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE donations_list (id INT AUTO_INCREMENT NOT NULL, donator_id_id INT NOT NULL, date DATE NOT NULL, montant INT NOT NULL, INDEX IDX_B22917B33CB2E5E7 (donator_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE donations_list ADD CONSTRAINT FK_B22917B33CB2E5E7 FOREIGN KEY (donator_id_id) REFERENCES donator (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donations_list DROP FOREIGN KEY FK_B22917B33CB2E5E7');
        $this->addSql('DROP TABLE donations_list');
    }
}
