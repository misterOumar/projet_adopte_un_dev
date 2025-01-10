<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250110003741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE developer_saved (company_id INT NOT NULL, developer_id INT NOT NULL, INDEX IDX_166B1372979B1AD6 (company_id), INDEX IDX_166B137264DD9267 (developer_id), PRIMARY KEY(company_id, developer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE developer_saved ADD CONSTRAINT FK_166B1372979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE developer_saved ADD CONSTRAINT FK_166B137264DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE developer_saved DROP FOREIGN KEY FK_166B1372979B1AD6');
        $this->addSql('ALTER TABLE developer_saved DROP FOREIGN KEY FK_166B137264DD9267');
        $this->addSql('DROP TABLE developer_saved');
    }
}
