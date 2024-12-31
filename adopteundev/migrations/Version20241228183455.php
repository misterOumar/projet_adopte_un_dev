<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241228183455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE developer DROP INDEX UNIQ_65FB8B9AE6ADA943, ADD INDEX IDX_65FB8B9AE6ADA943 (cat_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_MOBILE ON developer (mobile)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE developer DROP INDEX IDX_65FB8B9AE6ADA943, ADD UNIQUE INDEX UNIQ_65FB8B9AE6ADA943 (cat_id)');
        $this->addSql('DROP INDEX UNIQ_MOBILE ON developer');
    }
}
