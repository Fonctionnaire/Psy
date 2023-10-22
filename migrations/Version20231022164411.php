<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231022164411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE testimony_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE testimony ADD testimony_category_id INT NOT NULL, DROP category');
        $this->addSql('ALTER TABLE testimony ADD CONSTRAINT FK_523C94871F0120E3 FOREIGN KEY (testimony_category_id) REFERENCES testimony_category (id)');
        $this->addSql('CREATE INDEX IDX_523C94871F0120E3 ON testimony (testimony_category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE testimony DROP FOREIGN KEY FK_523C94871F0120E3');
        $this->addSql('DROP TABLE testimony_category');
        $this->addSql('DROP INDEX IDX_523C94871F0120E3 ON testimony');
        $this->addSql('ALTER TABLE testimony ADD category VARCHAR(255) NOT NULL, DROP testimony_category_id');
    }
}
