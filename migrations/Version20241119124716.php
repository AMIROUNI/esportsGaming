<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241119124716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation_tournoi ADD tournoi_id INT NOT NULL');
        $this->addSql('ALTER TABLE participation_tournoi ADD CONSTRAINT FK_53D1681EF607770A FOREIGN KEY (tournoi_id) REFERENCES tournoi (id)');
        $this->addSql('CREATE INDEX IDX_53D1681EF607770A ON participation_tournoi (tournoi_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation_tournoi DROP FOREIGN KEY FK_53D1681EF607770A');
        $this->addSql('DROP INDEX IDX_53D1681EF607770A ON participation_tournoi');
        $this->addSql('ALTER TABLE participation_tournoi DROP tournoi_id');
    }
}
