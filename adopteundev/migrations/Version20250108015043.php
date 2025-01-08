<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108015043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_view (id INT AUTO_INCREMENT NOT NULL, poste_id INT NOT NULL, user_id INT NOT NULL, viewed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_37A8CC85A0905086 (poste_id), INDEX IDX_37A8CC85A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_view ADD CONSTRAINT FK_37A8CC85A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE post_view ADD CONSTRAINT FK_37A8CC85A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_view DROP FOREIGN KEY FK_37A8CC85A0905086');
        $this->addSql('ALTER TABLE post_view DROP FOREIGN KEY FK_37A8CC85A76ED395');
        $this->addSql('DROP TABLE post_view');
    }
}
