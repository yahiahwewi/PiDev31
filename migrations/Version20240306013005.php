<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306013005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donations_list DROP FOREIGN KEY FK_B22917B33CB2E5E7');
        $this->addSql('DROP INDEX IDX_B22917B33CB2E5E7 ON donations_list');
        $this->addSql('ALTER TABLE donations_list ADD donator_id INT NOT NULL, DROP donator_id_id, DROP project_id');
        $this->addSql('ALTER TABLE donations_list ADD CONSTRAINT FK_B22917B3831BACAF FOREIGN KEY (donator_id) REFERENCES donator (id)');
        $this->addSql('CREATE INDEX IDX_B22917B3831BACAF ON donations_list (donator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donations_list DROP FOREIGN KEY FK_B22917B3831BACAF');
        $this->addSql('DROP INDEX IDX_B22917B3831BACAF ON donations_list');
        $this->addSql('ALTER TABLE donations_list ADD project_id INT NOT NULL, CHANGE donator_id donator_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE donations_list ADD CONSTRAINT FK_B22917B33CB2E5E7 FOREIGN KEY (donator_id_id) REFERENCES donator (id)');
        $this->addSql('CREATE INDEX IDX_B22917B33CB2E5E7 ON donations_list (donator_id_id)');
    }
}
