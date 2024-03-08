<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305232116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE donations_list (id INT AUTO_INCREMENT NOT NULL, donator_id_id INT NOT NULL, date DATE NOT NULL, montant INT NOT NULL, INDEX IDX_B22917B33CB2E5E7 (donator_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donator_list (id INT AUTO_INCREMENT NOT NULL, don_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE donations_list ADD CONSTRAINT FK_B22917B33CB2E5E7 FOREIGN KEY (donator_id_id) REFERENCES donator (id)');
        $this->addSql('ALTER TABLE donator ADD prenom VARCHAR(255) NOT NULL, ADD montant INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donations_list DROP FOREIGN KEY FK_B22917B33CB2E5E7');
        $this->addSql('DROP TABLE donations_list');
        $this->addSql('DROP TABLE donator_list');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE donator DROP prenom, DROP montant');
    }
}
