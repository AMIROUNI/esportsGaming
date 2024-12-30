<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241226104518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation_tournoi DROP FOREIGN KEY FK_53D1681E2F43A116');
        $this->addSql('DROP INDEX IDX_53D1681E2F43A116 ON participation_tournoi');
        $this->addSql('ALTER TABLE participation_tournoi CHANGE gamer_id group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participation_tournoi ADD CONSTRAINT FK_53D1681EFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)');
        $this->addSql('CREATE INDEX IDX_53D1681EFE54D947 ON participation_tournoi (group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation_tournoi DROP FOREIGN KEY FK_53D1681EFE54D947');
        $this->addSql('DROP INDEX IDX_53D1681EFE54D947 ON participation_tournoi');
        $this->addSql('ALTER TABLE participation_tournoi CHANGE group_id gamer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participation_tournoi ADD CONSTRAINT FK_53D1681E2F43A116 FOREIGN KEY (gamer_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_53D1681E2F43A116 ON participation_tournoi (gamer_id)');
    }
}
