<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241231153701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit CHANGE produit_category_id produit_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC276C5ED966 FOREIGN KEY (produit_category_id) REFERENCES produit_category (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC276C5ED966 ON produit (produit_category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC276C5ED966');
        $this->addSql('DROP INDEX IDX_29A5EC276C5ED966 ON produit');
        $this->addSql('ALTER TABLE produit CHANGE produit_category_id produit_category_id INT NOT NULL');
    }
}