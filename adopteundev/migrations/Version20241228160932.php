<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241228160932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE developer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, cat_id INT NOT NULL, nom VARCHAR(80) NOT NULL, prenom VARCHAR(180) NOT NULL, mobile VARCHAR(10) NOT NULL, salaire_min DOUBLE PRECISION DEFAULT NULL, is_disponible TINYINT(1) DEFAULT NULL, experience INT DEFAULT NULL, ville VARCHAR(180) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, biographie LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_65FB8B9AA76ED395 (user_id), UNIQUE INDEX UNIQ_65FB8B9AE6ADA943 (cat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE developer ADD CONSTRAINT FK_65FB8B9AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE developer ADD CONSTRAINT FK_65FB8B9AE6ADA943 FOREIGN KEY (cat_id) REFERENCES categorie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE developer DROP FOREIGN KEY FK_65FB8B9AA76ED395');
        $this->addSql('ALTER TABLE developer DROP FOREIGN KEY FK_65FB8B9AE6ADA943');
        $this->addSql('DROP TABLE developer');
    }
}
