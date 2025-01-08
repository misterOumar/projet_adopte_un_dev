<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250107220543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE developer_favorites (developer_id INT NOT NULL, poste_id INT NOT NULL, INDEX IDX_E0F32C0464DD9267 (developer_id), INDEX IDX_E0F32C04A0905086 (poste_id), PRIMARY KEY(developer_id, poste_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE developer_favorites ADD CONSTRAINT FK_E0F32C0464DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE developer_favorites ADD CONSTRAINT FK_E0F32C04A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidature CHANGE statut statut VARCHAR(15) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE developer_favorites DROP FOREIGN KEY FK_E0F32C0464DD9267');
        $this->addSql('ALTER TABLE developer_favorites DROP FOREIGN KEY FK_E0F32C04A0905086');
        $this->addSql('DROP TABLE developer_favorites');
        $this->addSql('ALTER TABLE candidature CHANGE statut statut VARCHAR(15) DEFAULT \'En cours\' NOT NULL');
    }
}
