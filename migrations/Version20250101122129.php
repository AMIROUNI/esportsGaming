<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250101122129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `group` ADD admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C5642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6DC044C5642B8210 ON `group` (admin_id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC276C5ED966 FOREIGN KEY (produit_category_id) REFERENCES produit_category (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC276C5ED966 ON produit (produit_category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C5642B8210');
        $this->addSql('DROP INDEX IDX_6DC044C5642B8210 ON `group`');
        $this->addSql('ALTER TABLE `group` DROP admin_id');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC276C5ED966');
        $this->addSql('DROP INDEX IDX_29A5EC276C5ED966 ON produit');
    }
}
