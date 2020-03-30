<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200324160616 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE appartement_vide (id INT AUTO_INCREMENT NOT NULL, rue VARCHAR(255) NOT NULL, arrondissement INT NOT NULL, etage VARCHAR(255) NOT NULL, type_appart VARCHAR(255) NOT NULL, prix_location INT NOT NULL, prix_charge INT NOT NULL, ascenceur TINYINT(1) NOT NULL, preavis TINYINT(1) NOT NULL, montant_cotisation INT NOT NULL, prix_total INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3E1729BBA');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3E1729BBA FOREIGN KEY (appartement_id) REFERENCES appartement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_appartement DROP date_visite');
        $this->addSql('ALTER TABLE appartement DROP FOREIGN KEY FK_71A6BD8D76C50E4A');
        $this->addSql('ALTER TABLE appartement ADD CONSTRAINT FK_71A6BD8D76C50E4A FOREIGN KEY (proprietaire_id) REFERENCES utilisateur (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE appartement_vide');
        $this->addSql('ALTER TABLE appartement DROP FOREIGN KEY FK_71A6BD8D76C50E4A');
        $this->addSql('ALTER TABLE appartement ADD CONSTRAINT FK_71A6BD8D76C50E4A FOREIGN KEY (proprietaire_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE client_appartement ADD date_visite DATE NOT NULL');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3E1729BBA');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3E1729BBA FOREIGN KEY (appartement_id) REFERENCES appartement (id)');
    }
}
