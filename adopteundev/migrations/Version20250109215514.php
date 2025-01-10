<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109215514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE developer_rating (id INT AUTO_INCREMENT NOT NULL, rate_developer_id INT NOT NULL, rating_developer_id INT NOT NULL, rating SMALLINT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2D696AB55BE25BD8 (rate_developer_id), INDEX IDX_2D696AB55AC2B4CF (rating_developer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE developer_rating ADD CONSTRAINT FK_2D696AB55BE25BD8 FOREIGN KEY (rate_developer_id) REFERENCES developer (id)');
        $this->addSql('ALTER TABLE developer_rating ADD CONSTRAINT FK_2D696AB55AC2B4CF FOREIGN KEY (rating_developer_id) REFERENCES developer (id)');
        $this->addSql('ALTER TABLE developer ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE developer_rating DROP FOREIGN KEY FK_2D696AB55BE25BD8');
        $this->addSql('ALTER TABLE developer_rating DROP FOREIGN KEY FK_2D696AB55AC2B4CF');
        $this->addSql('DROP TABLE developer_rating');
        $this->addSql('ALTER TABLE developer DROP uuid');
    }
}
