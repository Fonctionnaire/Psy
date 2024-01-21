<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231203140737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE forum_answer (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, forum_subject_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_ban TINYINT(1) NOT NULL, is_reported TINYINT(1) NOT NULL, INDEX IDX_C27279F4F675F31B (author_id), INDEX IDX_C27279F4800861C2 (forum_subject_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_subject (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, forum_category_id INT NOT NULL, subject VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_rule TINYINT(1) NOT NULL, nb_of_view INT NOT NULL, is_reported TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_A02730BF675F31B (author_id), INDEX IDX_A02730B14721E40 (forum_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum_answer ADD CONSTRAINT FK_C27279F4F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_answer ADD CONSTRAINT FK_C27279F4800861C2 FOREIGN KEY (forum_subject_id) REFERENCES forum_subject (id)');
        $this->addSql('ALTER TABLE forum_subject ADD CONSTRAINT FK_A02730BF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_subject ADD CONSTRAINT FK_A02730B14721E40 FOREIGN KEY (forum_category_id) REFERENCES forum_category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forum_answer DROP FOREIGN KEY FK_C27279F4F675F31B');
        $this->addSql('ALTER TABLE forum_answer DROP FOREIGN KEY FK_C27279F4800861C2');
        $this->addSql('ALTER TABLE forum_subject DROP FOREIGN KEY FK_A02730BF675F31B');
        $this->addSql('ALTER TABLE forum_subject DROP FOREIGN KEY FK_A02730B14721E40');
        $this->addSql('DROP TABLE forum_answer');
        $this->addSql('DROP TABLE forum_category');
        $this->addSql('DROP TABLE forum_subject');
    }
}
