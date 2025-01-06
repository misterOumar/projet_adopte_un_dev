<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250105175303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE poste_technologie (poste_id INT NOT NULL, technologie_id INT NOT NULL, INDEX IDX_65277250A0905086 (poste_id), INDEX IDX_65277250261A27D2 (technologie_id), PRIMARY KEY(poste_id, technologie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE poste_technologie ADD CONSTRAINT FK_65277250A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE poste_technologie ADD CONSTRAINT FK_65277250261A27D2 FOREIGN KEY (technologie_id) REFERENCES technologie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE poste_technologie DROP FOREIGN KEY FK_65277250A0905086');
        $this->addSql('ALTER TABLE poste_technologie DROP FOREIGN KEY FK_65277250261A27D2');
        $this->addSql('DROP TABLE poste_technologie');
    }
}
