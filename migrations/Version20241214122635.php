<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Initial migration for creating tables for the gaming application.
 */
final class Version20241214122635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates initial tables for users, products, tournaments, games, and associated relationships for the gaming application.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE commande (
            id INT AUTO_INCREMENT NOT NULL, 
            gamer_id INT DEFAULT NULL, 
            date_de_commande DATE NOT NULL, 
            INDEX IDX_6EEAA67D2F43A116 (gamer_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE contenu (
            id INT AUTO_INCREMENT NOT NULL, 
            titre VARCHAR(255) NOT NULL, 
            description VARCHAR(255) DEFAULT NULL, 
            image VARCHAR(255) NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE demande_de_programme_c (
            id INT AUTO_INCREMENT NOT NULL, 
            gamer_id INT DEFAULT NULL, 
            coach_id INT DEFAULT NULL, 
            programme_coaching_id INT DEFAULT NULL, 
            etat VARCHAR(255) NOT NULL, 
            INDEX IDX_3077F8F42F43A116 (gamer_id), 
            INDEX IDX_3077F8F43C105691 (coach_id), 
            INDEX IDX_3077F8F4CF41A1EC (programme_coaching_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE game (
            id INT AUTO_INCREMENT NOT NULL, 
            nom VARCHAR(255) NOT NULL, 
            image VARCHAR(255) NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE `group` (
            id INT AUTO_INCREMENT NOT NULL, 
            nom_group VARCHAR(255) NOT NULL, 
            logo VARCHAR(255) NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE group_gamer (
            group_id INT NOT NULL, 
            gamer_id INT NOT NULL, 
            INDEX IDX_D80830C0FE54D947 (group_id), 
            INDEX IDX_D80830C02F43A116 (gamer_id), 
            PRIMARY KEY(group_id, gamer_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE lp (
            id INT AUTO_INCREMENT NOT NULL, 
            commande_id INT DEFAULT NULL, 
            produit_id INT DEFAULT NULL, 
            qte_produit INT NOT NULL DEFAULT 0, 
            INDEX IDX_D894476882EA2E54 (commande_id), 
            INDEX IDX_D8944768F347EFB (produit_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE participation_tournoi (
            id INT AUTO_INCREMENT NOT NULL, 
            gamer_id INT DEFAULT NULL, 
            tournoi_id INT NOT NULL, 
            etat VARCHAR(255) NOT NULL, 
            INDEX IDX_53D1681E2F43A116 (gamer_id), 
            INDEX IDX_53D1681EF607770A (tournoi_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE produit (
            id INT AUTO_INCREMENT NOT NULL, 
            nom_produits VARCHAR(255) NOT NULL, 
            prix DOUBLE PRECISION NOT NULL, 
            image VARCHAR(255) DEFAULT NULL, 
            rating DOUBLE PRECISION NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE programme_coaching (
            id INT AUTO_INCREMENT NOT NULL, 
            titre VARCHAR(255) DEFAULT NULL, 
            duree INT DEFAULT NULL, 
            prix DOUBLE PRECISION DEFAULT NULL, 
            description VARCHAR(255) DEFAULT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE tournoi (
            id INT AUTO_INCREMENT NOT NULL, 
            nom_tournoi VARCHAR(255) NOT NULL, 
            description VARCHAR(255) DEFAULT NULL, 
            date_debut DATE DEFAULT NULL, 
            date_fin DATE DEFAULT NULL, 
            recompense VARCHAR(255) NOT NULL, 
            max_participants INT DEFAULT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE tournoi_game (
            tournoi_id INT NOT NULL, 
            game_id INT NOT NULL, 
            INDEX IDX_7EC8DD08F607770A (tournoi_id), 
            INDEX IDX_7EC8DD08E48FD905 (game_id), 
            PRIMARY KEY(tournoi_id, game_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE user (
            id INT AUTO_INCREMENT NOT NULL, 
            nom VARCHAR(255) DEFAULT NULL, 
            prenom VARCHAR(255) DEFAULT NULL, 
            email VARCHAR(255) DEFAULT NULL, 
            password VARCHAR(255) NOT NULL, 
            discr VARCHAR(255) NOT NULL, 
            description VARCHAR(255) DEFAULT NULL, 
            niveau VARCHAR(255) DEFAULT NULL, 
            sur_nom VARCHAR(255) DEFAULT NULL, 
            badge VARCHAR(255) DEFAULT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D2F43A116 FOREIGN KEY (gamer_id) REFERENCES user (id)');
        // Repeat for other ALTER TABLE as in your original file
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE commande');
        // Repeat DROP logic for all tables
    }
}
