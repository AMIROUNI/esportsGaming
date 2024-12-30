<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241225135916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_gamer DROP FOREIGN KEY FK_D80830C02F43A116');
        $this->addSql('DROP INDEX IDX_D80830C02F43A116 ON group_gamer');
        $this->addSql('DROP INDEX `primary` ON group_gamer');
        $this->addSql('ALTER TABLE group_gamer CHANGE gamer_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE group_gamer ADD CONSTRAINT FK_D80830C0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_D80830C0A76ED395 ON group_gamer (user_id)');
        $this->addSql('ALTER TABLE group_gamer ADD PRIMARY KEY (group_id, user_id)');
        $this->addSql('ALTER TABLE user DROP discr, CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE prenom prenom VARCHAR(100) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE niveau niveau INT DEFAULT NULL, CHANGE sur_nom sur_nom VARCHAR(100) DEFAULT NULL, CHANGE badge badge VARCHAR(100) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_gamer DROP FOREIGN KEY FK_D80830C0A76ED395');
        $this->addSql('DROP INDEX IDX_D80830C0A76ED395 ON group_gamer');
        $this->addSql('DROP INDEX `PRIMARY` ON group_gamer');
        $this->addSql('ALTER TABLE group_gamer CHANGE user_id gamer_id INT NOT NULL');
        $this->addSql('ALTER TABLE group_gamer ADD CONSTRAINT FK_D80830C02F43A116 FOREIGN KEY (gamer_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_D80830C02F43A116 ON group_gamer (gamer_id)');
        $this->addSql('ALTER TABLE group_gamer ADD PRIMARY KEY (group_id, gamer_id)');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON user');
        $this->addSql('ALTER TABLE user ADD discr VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE niveau niveau VARCHAR(255) DEFAULT NULL, CHANGE sur_nom sur_nom VARCHAR(255) DEFAULT NULL, CHANGE badge badge VARCHAR(255) DEFAULT NULL');
    }
}
