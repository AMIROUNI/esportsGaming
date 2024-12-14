<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241214135301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY commande_ibfk_1');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D2F43A116 FOREIGN KEY (gamer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contenu ADD data DATETIME NOT NULL');
        $this->addSql('ALTER TABLE demande_de_programme_c DROP FOREIGN KEY demande_de_programme_c_ibfk_1');
        $this->addSql('ALTER TABLE demande_de_programme_c DROP FOREIGN KEY demande_de_programme_c_ibfk_2');
        $this->addSql('ALTER TABLE demande_de_programme_c DROP FOREIGN KEY demande_de_programme_c_ibfk_3');
        $this->addSql('ALTER TABLE demande_de_programme_c ADD CONSTRAINT FK_3077F8F42F43A116 FOREIGN KEY (gamer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_de_programme_c ADD CONSTRAINT FK_3077F8F43C105691 FOREIGN KEY (coach_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_de_programme_c ADD CONSTRAINT FK_3077F8F4CF41A1EC FOREIGN KEY (programme_coaching_id) REFERENCES programme_coaching (id)');
        $this->addSql('ALTER TABLE game CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lp DROP FOREIGN KEY lp_ibfk_2');
        $this->addSql('ALTER TABLE lp DROP FOREIGN KEY lp_ibfk_1');
        $this->addSql('ALTER TABLE lp CHANGE qte_produit qte_produit INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lp ADD CONSTRAINT FK_D894476882EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE lp ADD CONSTRAINT FK_D8944768F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE participation_tournoi DROP FOREIGN KEY participation_tournoi_ibfk_1');
        $this->addSql('ALTER TABLE participation_tournoi DROP FOREIGN KEY participation_tournoi_ibfk_2');
        $this->addSql('ALTER TABLE participation_tournoi ADD CONSTRAINT FK_53D1681E2F43A116 FOREIGN KEY (gamer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE participation_tournoi ADD CONSTRAINT FK_53D1681EF607770A FOREIGN KEY (tournoi_id) REFERENCES tournoi (id)');
        $this->addSql('ALTER TABLE produit CHANGE image image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D2F43A116');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT commande_ibfk_1 FOREIGN KEY (gamer_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE contenu DROP data');
        $this->addSql('ALTER TABLE demande_de_programme_c DROP FOREIGN KEY FK_3077F8F42F43A116');
        $this->addSql('ALTER TABLE demande_de_programme_c DROP FOREIGN KEY FK_3077F8F43C105691');
        $this->addSql('ALTER TABLE demande_de_programme_c DROP FOREIGN KEY FK_3077F8F4CF41A1EC');
        $this->addSql('ALTER TABLE demande_de_programme_c ADD CONSTRAINT demande_de_programme_c_ibfk_1 FOREIGN KEY (gamer_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE demande_de_programme_c ADD CONSTRAINT demande_de_programme_c_ibfk_2 FOREIGN KEY (coach_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE demande_de_programme_c ADD CONSTRAINT demande_de_programme_c_ibfk_3 FOREIGN KEY (programme_coaching_id) REFERENCES programme_coaching (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE game CHANGE image image BLOB NOT NULL');
        $this->addSql('ALTER TABLE lp DROP FOREIGN KEY FK_D894476882EA2E54');
        $this->addSql('ALTER TABLE lp DROP FOREIGN KEY FK_D8944768F347EFB');
        $this->addSql('ALTER TABLE lp CHANGE qte_produit qte_produit INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE lp ADD CONSTRAINT lp_ibfk_2 FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lp ADD CONSTRAINT lp_ibfk_1 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation_tournoi DROP FOREIGN KEY FK_53D1681E2F43A116');
        $this->addSql('ALTER TABLE participation_tournoi DROP FOREIGN KEY FK_53D1681EF607770A');
        $this->addSql('ALTER TABLE participation_tournoi ADD CONSTRAINT participation_tournoi_ibfk_1 FOREIGN KEY (gamer_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE participation_tournoi ADD CONSTRAINT participation_tournoi_ibfk_2 FOREIGN KEY (tournoi_id) REFERENCES tournoi (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit CHANGE image image BLOB DEFAULT NULL');
    }
}
