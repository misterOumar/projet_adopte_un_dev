<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250104013640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_497DD6346C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom VARCHAR(180) NOT NULL, phone VARCHAR(20) NOT NULL, ville VARCHAR(100) NOT NULL, adresse VARCHAR(180) DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4FBF094FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE developer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, cat_id INT NOT NULL, nom VARCHAR(80) NOT NULL, prenom VARCHAR(180) NOT NULL, mobile VARCHAR(10) NOT NULL, salaire_min DOUBLE PRECISION DEFAULT NULL, is_disponible TINYINT(1) DEFAULT NULL, experience INT DEFAULT NULL, ville VARCHAR(180) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, biographie LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_65FB8B9AA76ED395 (user_id), INDEX IDX_65FB8B9AE6ADA943 (cat_id), UNIQUE INDEX UNIQ_MOBILE (mobile), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poste (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, company_id INT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', titre VARCHAR(180) NOT NULL, salaire_min DOUBLE PRECISION NOT NULL, type VARCHAR(20) NOT NULL, experience_requis INT NOT NULL, ville VARCHAR(50) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description LONGTEXT DEFAULT NULL, date_limite DATE NOT NULL, INDEX IDX_7C890FABBCF5E72D (categorie_id), INDEX IDX_7C890FAB979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technologie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_AD8136746C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, is_bloqued TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_IDENTIFIER_UUID (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE developer ADD CONSTRAINT FK_65FB8B9AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE developer ADD CONSTRAINT FK_65FB8B9AE6ADA943 FOREIGN KEY (cat_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE poste ADD CONSTRAINT FK_7C890FABBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE poste ADD CONSTRAINT FK_7C890FAB979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FA76ED395');
        $this->addSql('ALTER TABLE developer DROP FOREIGN KEY FK_65FB8B9AA76ED395');
        $this->addSql('ALTER TABLE developer DROP FOREIGN KEY FK_65FB8B9AE6ADA943');
        $this->addSql('ALTER TABLE poste DROP FOREIGN KEY FK_7C890FABBCF5E72D');
        $this->addSql('ALTER TABLE poste DROP FOREIGN KEY FK_7C890FAB979B1AD6');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE developer');
        $this->addSql('DROP TABLE poste');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE technologie');
        $this->addSql('DROP TABLE user');
    }
}
