<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109222953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidature (id INT AUTO_INCREMENT NOT NULL, poste_id INT DEFAULT NULL, developer_id INT NOT NULL, fichier_id INT NOT NULL, date DATE NOT NULL, statut VARCHAR(15) NOT NULL, reponse VARCHAR(255) DEFAULT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_E33BD3B8A0905086 (poste_id), INDEX IDX_E33BD3B864DD9267 (developer_id), INDEX IDX_E33BD3B8F915CFE (fichier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_497DD6346C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom VARCHAR(180) NOT NULL, phone VARCHAR(20) NOT NULL, ville VARCHAR(100) NOT NULL, adresse VARCHAR(180) DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4FBF094FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cv (id INT AUTO_INCREMENT NOT NULL, developer_id INT NOT NULL, fichier_id INT NOT NULL, INDEX IDX_B66FFE9264DD9267 (developer_id), INDEX IDX_B66FFE92F915CFE (fichier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE developer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, cat_id INT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', nom VARCHAR(80) NOT NULL, prenom VARCHAR(180) NOT NULL, mobile VARCHAR(10) NOT NULL, salaire_min DOUBLE PRECISION DEFAULT NULL, is_disponible TINYINT(1) DEFAULT NULL, experience INT DEFAULT NULL, ville VARCHAR(180) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, biographie LONGTEXT DEFAULT NULL, mobile_visible TINYINT(1) NOT NULL, salaire_visible TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_65FB8B9AA76ED395 (user_id), INDEX IDX_65FB8B9AE6ADA943 (cat_id), UNIQUE INDEX UNIQ_MOBILE (mobile), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE developer_technologie (developer_id INT NOT NULL, technologie_id INT NOT NULL, INDEX IDX_DF4CEFA964DD9267 (developer_id), INDEX IDX_DF4CEFA9261A27D2 (technologie_id), PRIMARY KEY(developer_id, technologie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE developer_favorites (developer_id INT NOT NULL, poste_id INT NOT NULL, INDEX IDX_E0F32C0464DD9267 (developer_id), INDEX IDX_E0F32C04A0905086 (poste_id), PRIMARY KEY(developer_id, poste_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE developer_rating (id INT AUTO_INCREMENT NOT NULL, rate_developer_id INT NOT NULL, rating_developer_id INT NOT NULL, rating SMALLINT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2D696AB55BE25BD8 (rate_developer_id), INDEX IDX_2D696AB55AC2B4CF (rating_developer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fichier (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(200) NOT NULL, reference VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, message VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_read TINYINT(1) NOT NULL, type VARCHAR(255) DEFAULT NULL, INDEX IDX_BF5476CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_view (id INT AUTO_INCREMENT NOT NULL, poste_id INT NOT NULL, user_id INT NOT NULL, viewed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_37A8CC85A0905086 (poste_id), INDEX IDX_37A8CC85A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poste (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, company_id INT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', titre VARCHAR(180) NOT NULL, salaire_min DOUBLE PRECISION NOT NULL, type VARCHAR(20) NOT NULL, experience_requis INT NOT NULL, ville VARCHAR(50) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description LONGTEXT DEFAULT NULL, date_limite DATE NOT NULL, INDEX IDX_7C890FABBCF5E72D (categorie_id), INDEX IDX_7C890FAB979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poste_technologie (poste_id INT NOT NULL, technologie_id INT NOT NULL, INDEX IDX_65277250A0905086 (poste_id), INDEX IDX_65277250261A27D2 (technologie_id), PRIMARY KEY(poste_id, technologie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technologie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_AD8136746C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, avatar_id INT DEFAULT NULL, uuid VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, is_bloqued TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D64986383B10 (avatar_id), UNIQUE INDEX UNIQ_IDENTIFIER_UUID (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B864DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8F915CFE FOREIGN KEY (fichier_id) REFERENCES fichier (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cv ADD CONSTRAINT FK_B66FFE9264DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id)');
        $this->addSql('ALTER TABLE cv ADD CONSTRAINT FK_B66FFE92F915CFE FOREIGN KEY (fichier_id) REFERENCES fichier (id)');
        $this->addSql('ALTER TABLE developer ADD CONSTRAINT FK_65FB8B9AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE developer ADD CONSTRAINT FK_65FB8B9AE6ADA943 FOREIGN KEY (cat_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE developer_technologie ADD CONSTRAINT FK_DF4CEFA964DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE developer_technologie ADD CONSTRAINT FK_DF4CEFA9261A27D2 FOREIGN KEY (technologie_id) REFERENCES technologie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE developer_favorites ADD CONSTRAINT FK_E0F32C0464DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE developer_favorites ADD CONSTRAINT FK_E0F32C04A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE developer_rating ADD CONSTRAINT FK_2D696AB55BE25BD8 FOREIGN KEY (rate_developer_id) REFERENCES developer (id)');
        $this->addSql('ALTER TABLE developer_rating ADD CONSTRAINT FK_2D696AB55AC2B4CF FOREIGN KEY (rating_developer_id) REFERENCES developer (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post_view ADD CONSTRAINT FK_37A8CC85A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE post_view ADD CONSTRAINT FK_37A8CC85A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE poste ADD CONSTRAINT FK_7C890FABBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE poste ADD CONSTRAINT FK_7C890FAB979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE poste_technologie ADD CONSTRAINT FK_65277250A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE poste_technologie ADD CONSTRAINT FK_65277250261A27D2 FOREIGN KEY (technologie_id) REFERENCES technologie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64986383B10 FOREIGN KEY (avatar_id) REFERENCES fichier (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8A0905086');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B864DD9267');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8F915CFE');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FA76ED395');
        $this->addSql('ALTER TABLE cv DROP FOREIGN KEY FK_B66FFE9264DD9267');
        $this->addSql('ALTER TABLE cv DROP FOREIGN KEY FK_B66FFE92F915CFE');
        $this->addSql('ALTER TABLE developer DROP FOREIGN KEY FK_65FB8B9AA76ED395');
        $this->addSql('ALTER TABLE developer DROP FOREIGN KEY FK_65FB8B9AE6ADA943');
        $this->addSql('ALTER TABLE developer_technologie DROP FOREIGN KEY FK_DF4CEFA964DD9267');
        $this->addSql('ALTER TABLE developer_technologie DROP FOREIGN KEY FK_DF4CEFA9261A27D2');
        $this->addSql('ALTER TABLE developer_favorites DROP FOREIGN KEY FK_E0F32C0464DD9267');
        $this->addSql('ALTER TABLE developer_favorites DROP FOREIGN KEY FK_E0F32C04A0905086');
        $this->addSql('ALTER TABLE developer_rating DROP FOREIGN KEY FK_2D696AB55BE25BD8');
        $this->addSql('ALTER TABLE developer_rating DROP FOREIGN KEY FK_2D696AB55AC2B4CF');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE post_view DROP FOREIGN KEY FK_37A8CC85A0905086');
        $this->addSql('ALTER TABLE post_view DROP FOREIGN KEY FK_37A8CC85A76ED395');
        $this->addSql('ALTER TABLE poste DROP FOREIGN KEY FK_7C890FABBCF5E72D');
        $this->addSql('ALTER TABLE poste DROP FOREIGN KEY FK_7C890FAB979B1AD6');
        $this->addSql('ALTER TABLE poste_technologie DROP FOREIGN KEY FK_65277250A0905086');
        $this->addSql('ALTER TABLE poste_technologie DROP FOREIGN KEY FK_65277250261A27D2');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64986383B10');
        $this->addSql('DROP TABLE candidature');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE cv');
        $this->addSql('DROP TABLE developer');
        $this->addSql('DROP TABLE developer_technologie');
        $this->addSql('DROP TABLE developer_favorites');
        $this->addSql('DROP TABLE developer_rating');
        $this->addSql('DROP TABLE fichier');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE post_view');
        $this->addSql('DROP TABLE poste');
        $this->addSql('DROP TABLE poste_technologie');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE technologie');
        $this->addSql('DROP TABLE user');
    }
}
