<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250106180133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cv (id INT AUTO_INCREMENT NOT NULL, developer_id INT NOT NULL, fichier_id INT NOT NULL, INDEX IDX_B66FFE9264DD9267 (developer_id), INDEX IDX_B66FFE92F915CFE (fichier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cv ADD CONSTRAINT FK_B66FFE9264DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id)');
        $this->addSql('ALTER TABLE cv ADD CONSTRAINT FK_B66FFE92F915CFE FOREIGN KEY (fichier_id) REFERENCES fichier (id)');
        $this->addSql('ALTER TABLE developer CHANGE is_disponible is_disponible TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cv DROP FOREIGN KEY FK_B66FFE9264DD9267');
        $this->addSql('ALTER TABLE cv DROP FOREIGN KEY FK_B66FFE92F915CFE');
        $this->addSql('DROP TABLE cv');
        $this->addSql('ALTER TABLE developer CHANGE is_disponible is_disponible TINYINT(1) DEFAULT NULL');
    }
}
