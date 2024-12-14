<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration auto-générée : Modifiée pour gérer les tables existantes.
 */
final class Version20241113142143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création des tables pour le projet eGaming, gestion des relations et des dépendances.';
    }

    public function up(Schema $schema): void
    {
        // Ajout de "IF NOT EXISTS" pour éviter les erreurs si les tables existent déjà.
        $this->addSql('CREATE TABLE IF NOT EXISTS commande (id INT AUTO_INCREMENT NOT NULL, gamer_id INT DEFAULT NULL, date_de_commande DATE NOT NULL, INDEX IDX_6EEAA67D2F43A116 (gamer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE IF NOT EXISTS contenu (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE IF NOT EXISTS demande_de_programme_c (id INT AUTO_INCREMENT NOT NULL, gamer_id INT DEFAULT NULL, coach_id INT DEFAULT NULL, programme_coaching_id INT DEFAULT NULL, etat VARCHAR(255) NOT NULL, INDEX IDX_3077F8F42F43A116 (gamer_id), INDEX IDX_3077F8F43C105691 (coach_id), INDEX IDX_3077F8F4CF41A1EC (programme_coaching_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE IF NOT EXISTS game (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE IF NOT EXISTS lp (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, produit_id INT DEFAULT NULL, qte_produit INT DEFAULT NULL, INDEX IDX_D894476882EA2E54 (commande_id), INDEX IDX_D8944768F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE IF NOT EXISTS participation_tournoi (id INT AUTO_INCREMENT NOT NULL, gamer_id INT DEFAULT NULL, etat VARCHAR(255) NOT NULL, INDEX IDX_53D1681E2F43A116 (gamer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE IF NOT EXISTS produit (id INT AUTO_INCREMENT NOT NULL, nom_produits VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE IF NOT EXISTS programme_coaching (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) DEFAULT NULL, duree INT DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE IF NOT EXISTS tournoi (id INT AUTO_INCREMENT NOT NULL, nom_tournoi VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, date_debut DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, recompense VARCHAR(255) NOT NULL, max_participants INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE IF NOT EXISTS tournoi_game (tournoi_id INT NOT NULL, game_id INT NOT NULL, INDEX IDX_7EC8DD08F607770A (tournoi_id), INDEX IDX_7EC8DD08E48FD905 (game_id), PRIMARY KEY(tournoi_id, game_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE IF NOT EXISTS user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, discr VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, niveau VARCHAR(255) DEFAULT NULL, sur_nom VARCHAR(255) DEFAULT NULL, badge VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE IF NOT EXISTS messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Ajout des contraintes
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D2F43A116 FOREIGN KEY (gamer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_de_programme_c ADD CONSTRAINT FK_3077F8F42F43A116 FOREIGN KEY (gamer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_de_programme_c ADD CONSTRAINT FK_3077F8F43C105691 FOREIGN KEY (coach_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_de_programme_c ADD CONSTRAINT FK_3077F8F4CF41A1EC FOREIGN KEY (programme_coaching_id) REFERENCES programme_coaching (id)');
        $this->addSql('ALTER TABLE lp ADD CONSTRAINT FK_D894476882EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE lp ADD CONSTRAINT FK_D8944768F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE participation_tournoi ADD CONSTRAINT FK_53D1681E2F43A116 FOREIGN KEY (gamer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tournoi_game ADD CONSTRAINT FK_7EC8DD08F607770A FOREIGN KEY (tournoi_id) REFERENCES tournoi (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournoi_game ADD CONSTRAINT FK_7EC8DD08E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // Suppression des tables
        $this->addSql('DROP TABLE IF EXISTS commande');
        $this->addSql('DROP TABLE IF EXISTS contenu');
        $this->addSql('DROP TABLE IF EXISTS demande_de_programme_c');
        $this->addSql('DROP TABLE IF EXISTS game');
        $this->addSql('DROP TABLE IF EXISTS lp');
        $this->addSql('DROP TABLE IF EXISTS participation_tournoi');
        $this->addSql('DROP TABLE IF EXISTS produit');
        $this->addSql('DROP TABLE IF EXISTS programme_coaching');
        $this->addSql('DROP TABLE IF EXISTS tournoi');
        $this->addSql('DROP TABLE IF EXISTS tournoi_game');
        $this->addSql('DROP TABLE IF EXISTS user');
        $this->addSql('DROP TABLE IF EXISTS messenger_messages');
    }
}
