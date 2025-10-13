<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251013140153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accessory (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, is_best TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, company VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, postal VARCHAR(20) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, phone VARCHAR(20) NOT NULL, type VARCHAR(50) DEFAULT NULL, INDEX IDX_D4E6F81A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE caracteristique (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_caracteristique (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE illustration (id INT AUTO_INCREMENT NOT NULL, trottinette_id INT NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_D67B9A42F6798F43 (trottinette_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE illustrationaccess (id INT AUTO_INCREMENT NOT NULL, accessory_id INT NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_EA75D19D27E8CC78 (accessory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trottinette (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, name_short VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, description_short LONGTEXT DEFAULT NULL, image VARCHAR(255) NOT NULL, is_best TINYINT(1) NOT NULL, is_header TINYINT(1) NOT NULL, header_image VARCHAR(255) DEFAULT NULL, header_btn_title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trottinette_accessory (id INT AUTO_INCREMENT NOT NULL, trottinette_id INT NOT NULL, accessory_id INT NOT NULL, INDEX IDX_B37F755EF6798F43 (trottinette_id), INDEX IDX_B37F755E27E8CC78 (accessory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trottinette_caracteristique (id INT AUTO_INCREMENT NOT NULL, trottinette_id INT DEFAULT NULL, caracteristique_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, value VARCHAR(255) DEFAULT NULL, INDEX IDX_22FC340CF6798F43 (trottinette_id), INDEX IDX_22FC340C1704EEB7 (caracteristique_id), INDEX IDX_22FC340CBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trottinette_description_section (id INT AUTO_INCREMENT NOT NULL, trottinette_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, section_order INT NOT NULL, INDEX IDX_B92E215BF6798F43 (trottinette_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(64) NOT NULL, last_name VARCHAR(64) NOT NULL, tel VARCHAR(16) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE illustration ADD CONSTRAINT FK_D67B9A42F6798F43 FOREIGN KEY (trottinette_id) REFERENCES trottinette (id)');
        $this->addSql('ALTER TABLE illustrationaccess ADD CONSTRAINT FK_EA75D19D27E8CC78 FOREIGN KEY (accessory_id) REFERENCES accessory (id)');
        $this->addSql('ALTER TABLE trottinette_accessory ADD CONSTRAINT FK_B37F755EF6798F43 FOREIGN KEY (trottinette_id) REFERENCES trottinette (id)');
        $this->addSql('ALTER TABLE trottinette_accessory ADD CONSTRAINT FK_B37F755E27E8CC78 FOREIGN KEY (accessory_id) REFERENCES accessory (id)');
        $this->addSql('ALTER TABLE trottinette_caracteristique ADD CONSTRAINT FK_22FC340CF6798F43 FOREIGN KEY (trottinette_id) REFERENCES trottinette (id)');
        $this->addSql('ALTER TABLE trottinette_caracteristique ADD CONSTRAINT FK_22FC340C1704EEB7 FOREIGN KEY (caracteristique_id) REFERENCES caracteristique (id)');
        $this->addSql('ALTER TABLE trottinette_caracteristique ADD CONSTRAINT FK_22FC340CBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_caracteristique (id)');
        $this->addSql('ALTER TABLE trottinette_description_section ADD CONSTRAINT FK_B92E215BF6798F43 FOREIGN KEY (trottinette_id) REFERENCES trottinette (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81A76ED395');
        $this->addSql('ALTER TABLE illustration DROP FOREIGN KEY FK_D67B9A42F6798F43');
        $this->addSql('ALTER TABLE illustrationaccess DROP FOREIGN KEY FK_EA75D19D27E8CC78');
        $this->addSql('ALTER TABLE trottinette_accessory DROP FOREIGN KEY FK_B37F755EF6798F43');
        $this->addSql('ALTER TABLE trottinette_accessory DROP FOREIGN KEY FK_B37F755E27E8CC78');
        $this->addSql('ALTER TABLE trottinette_caracteristique DROP FOREIGN KEY FK_22FC340CF6798F43');
        $this->addSql('ALTER TABLE trottinette_caracteristique DROP FOREIGN KEY FK_22FC340C1704EEB7');
        $this->addSql('ALTER TABLE trottinette_caracteristique DROP FOREIGN KEY FK_22FC340CBCF5E72D');
        $this->addSql('ALTER TABLE trottinette_description_section DROP FOREIGN KEY FK_B92E215BF6798F43');
        $this->addSql('DROP TABLE accessory');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE caracteristique');
        $this->addSql('DROP TABLE categorie_caracteristique');
        $this->addSql('DROP TABLE illustration');
        $this->addSql('DROP TABLE illustrationaccess');
        $this->addSql('DROP TABLE trottinette');
        $this->addSql('DROP TABLE trottinette_accessory');
        $this->addSql('DROP TABLE trottinette_caracteristique');
        $this->addSql('DROP TABLE trottinette_description_section');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
