<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241230143727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matches (id INT AUTO_INCREMENT NOT NULL, group_a_id INT NOT NULL, group_b_id INT NOT NULL, tournoi_id INT NOT NULL, match_date DATETIME NOT NULL, INDEX IDX_62615BA12840828 (group_a_id), INDEX IDX_62615BA31A7C6 (group_b_id), INDEX IDX_62615BAF607770A (tournoi_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BA12840828 FOREIGN KEY (group_a_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BA31A7C6 FOREIGN KEY (group_b_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BAF607770A FOREIGN KEY (tournoi_id) REFERENCES tournoi (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA12840828');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA31A7C6');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BAF607770A');
        $this->addSql('DROP TABLE matches');
    }
}
