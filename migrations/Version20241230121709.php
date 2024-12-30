<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241230121709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_category_contenu (blog_category_id INT NOT NULL, contenu_id INT NOT NULL, INDEX IDX_3A196BA7CB76011C (blog_category_id), INDEX IDX_3A196BA73C1CC488 (contenu_id), PRIMARY KEY(blog_category_id, contenu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contenu_blog_category (contenu_id INT NOT NULL, blog_category_id INT NOT NULL, INDEX IDX_9030C01F3C1CC488 (contenu_id), INDEX IDX_9030C01FCB76011C (blog_category_id), PRIMARY KEY(contenu_id, blog_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_category_contenu ADD CONSTRAINT FK_3A196BA7CB76011C FOREIGN KEY (blog_category_id) REFERENCES blog_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_category_contenu ADD CONSTRAINT FK_3A196BA73C1CC488 FOREIGN KEY (contenu_id) REFERENCES contenu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contenu_blog_category ADD CONSTRAINT FK_9030C01F3C1CC488 FOREIGN KEY (contenu_id) REFERENCES contenu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contenu_blog_category ADD CONSTRAINT FK_9030C01FCB76011C FOREIGN KEY (blog_category_id) REFERENCES blog_category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_category_contenu DROP FOREIGN KEY FK_3A196BA7CB76011C');
        $this->addSql('ALTER TABLE blog_category_contenu DROP FOREIGN KEY FK_3A196BA73C1CC488');
        $this->addSql('ALTER TABLE contenu_blog_category DROP FOREIGN KEY FK_9030C01F3C1CC488');
        $this->addSql('ALTER TABLE contenu_blog_category DROP FOREIGN KEY FK_9030C01FCB76011C');
        $this->addSql('DROP TABLE blog_category');
        $this->addSql('DROP TABLE blog_category_contenu');
        $this->addSql('DROP TABLE contenu_blog_category');
    }
}
