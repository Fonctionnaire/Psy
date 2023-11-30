<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231130111942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, subject VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, is_terms_accepted TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', email VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, is_viewed TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE testimony (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, testimony_category_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', content LONGTEXT NOT NULL, is_validated TINYINT(1) NOT NULL, token BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_523C94875F37A13B (token), UNIQUE INDEX UNIQ_523C9487A76ED395 (user_id), INDEX IDX_523C94871F0120E3 (testimony_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE testimony_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', username VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, is_account_validated TINYINT(1) NOT NULL, is_ban TINYINT(1) NOT NULL, registration_token BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', is_accepted_terms TINYINT(1) NOT NULL, reset_password_token BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', is_volunteer TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_conversation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', token BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', is_viewed TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_A425AEBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_message (id INT AUTO_INCREMENT NOT NULL, user_conversation_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_valid TINYINT(1) NOT NULL, content LONGTEXT NOT NULL, is_admin TINYINT(1) NOT NULL, INDEX IDX_EEB02E751B706F19 (user_conversation_id), INDEX IDX_EEB02E75A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_review (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', rate INT NOT NULL, message LONGTEXT NOT NULL, is_validated TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1C119AFBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE testimony ADD CONSTRAINT FK_523C9487A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE testimony ADD CONSTRAINT FK_523C94871F0120E3 FOREIGN KEY (testimony_category_id) REFERENCES testimony_category (id)');
        $this->addSql('ALTER TABLE user_conversation ADD CONSTRAINT FK_A425AEBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_message ADD CONSTRAINT FK_EEB02E751B706F19 FOREIGN KEY (user_conversation_id) REFERENCES user_conversation (id)');
        $this->addSql('ALTER TABLE user_message ADD CONSTRAINT FK_EEB02E75A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_review ADD CONSTRAINT FK_1C119AFBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE testimony DROP FOREIGN KEY FK_523C9487A76ED395');
        $this->addSql('ALTER TABLE testimony DROP FOREIGN KEY FK_523C94871F0120E3');
        $this->addSql('ALTER TABLE user_conversation DROP FOREIGN KEY FK_A425AEBA76ED395');
        $this->addSql('ALTER TABLE user_message DROP FOREIGN KEY FK_EEB02E751B706F19');
        $this->addSql('ALTER TABLE user_message DROP FOREIGN KEY FK_EEB02E75A76ED395');
        $this->addSql('ALTER TABLE user_review DROP FOREIGN KEY FK_1C119AFBA76ED395');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE testimony');
        $this->addSql('DROP TABLE testimony_category');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_conversation');
        $this->addSql('DROP TABLE user_message');
        $this->addSql('DROP TABLE user_review');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
