<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108230535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE saved_developer DROP FOREIGN KEY FK_5020C267979B1AD6');
        $this->addSql('ALTER TABLE saved_developer DROP FOREIGN KEY FK_5020C26764DD9267');
        $this->addSql('ALTER TABLE saved_post DROP FOREIGN KEY FK_54B59E98A0905086');
        $this->addSql('ALTER TABLE saved_post DROP FOREIGN KEY FK_54B59E9864DD9267');
        $this->addSql('ALTER TABLE developer_technologie DROP FOREIGN KEY FK_DF4CEFA964DD9267');
        $this->addSql('ALTER TABLE developer_technologie DROP FOREIGN KEY FK_DF4CEFA9261A27D2');
        $this->addSql('DROP TABLE saved_developer');
        $this->addSql('DROP TABLE saved_post');
        $this->addSql('DROP TABLE developer_technologie');
        $this->addSql('ALTER TABLE developer DROP mobile_visible, DROP salaire_visible, CHANGE is_disponible is_disponible TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE saved_developer (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, developer_id INT NOT NULL, saved_at DATE NOT NULL, INDEX IDX_5020C267979B1AD6 (company_id), INDEX IDX_5020C26764DD9267 (developer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE saved_post (id INT AUTO_INCREMENT NOT NULL, developer_id INT NOT NULL, poste_id INT NOT NULL, saved_at DATE NOT NULL, expired_at DATE NOT NULL, INDEX IDX_54B59E9864DD9267 (developer_id), INDEX IDX_54B59E98A0905086 (poste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE developer_technologie (developer_id INT NOT NULL, technologie_id INT NOT NULL, INDEX IDX_DF4CEFA9261A27D2 (technologie_id), INDEX IDX_DF4CEFA964DD9267 (developer_id), PRIMARY KEY(developer_id, technologie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE saved_developer ADD CONSTRAINT FK_5020C267979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE saved_developer ADD CONSTRAINT FK_5020C26764DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE saved_post ADD CONSTRAINT FK_54B59E98A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE saved_post ADD CONSTRAINT FK_54B59E9864DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE developer_technologie ADD CONSTRAINT FK_DF4CEFA964DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE developer_technologie ADD CONSTRAINT FK_DF4CEFA9261A27D2 FOREIGN KEY (technologie_id) REFERENCES technologie (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE developer ADD mobile_visible TINYINT(1) NOT NULL, ADD salaire_visible TINYINT(1) NOT NULL, CHANGE is_disponible is_disponible TINYINT(1) NOT NULL');
    }
}
