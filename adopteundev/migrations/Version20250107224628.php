<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250107224628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE saved_developer (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, developer_id INT NOT NULL, INDEX IDX_5020C267979B1AD6 (company_id), INDEX IDX_5020C26764DD9267 (developer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saved_post (id INT AUTO_INCREMENT NOT NULL, developer_id INT NOT NULL, poste_id INT NOT NULL, INDEX IDX_54B59E9864DD9267 (developer_id), INDEX IDX_54B59E98A0905086 (poste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE saved_developer ADD CONSTRAINT FK_5020C267979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE saved_developer ADD CONSTRAINT FK_5020C26764DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id)');
        $this->addSql('ALTER TABLE saved_post ADD CONSTRAINT FK_54B59E9864DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id)');
        $this->addSql('ALTER TABLE saved_post ADD CONSTRAINT FK_54B59E98A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE saved_developer DROP FOREIGN KEY FK_5020C267979B1AD6');
        $this->addSql('ALTER TABLE saved_developer DROP FOREIGN KEY FK_5020C26764DD9267');
        $this->addSql('ALTER TABLE saved_post DROP FOREIGN KEY FK_54B59E9864DD9267');
        $this->addSql('ALTER TABLE saved_post DROP FOREIGN KEY FK_54B59E98A0905086');
        $this->addSql('DROP TABLE saved_developer');
        $this->addSql('DROP TABLE saved_post');
    }
}
