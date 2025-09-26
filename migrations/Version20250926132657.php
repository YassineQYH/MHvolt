<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250926132657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE header');
        $this->addSql('ALTER TABLE illustration DROP FOREIGN KEY FK_D67B9A4264F0FC18');
        $this->addSql('DROP INDEX IDX_D67B9A4264F0FC18 ON illustration');
        $this->addSql('ALTER TABLE illustration CHANGE trotinette_id trottinette_id INT NOT NULL');
        $this->addSql('ALTER TABLE illustration ADD CONSTRAINT FK_D67B9A42F6798F43 FOREIGN KEY (trottinette_id) REFERENCES trottinette (id)');
        $this->addSql('CREATE INDEX IDX_D67B9A42F6798F43 ON illustration (trottinette_id)');
        $this->addSql('ALTER TABLE trottinette ADD is_header TINYINT(1) NOT NULL, ADD header_image VARCHAR(255) DEFAULT NULL, ADD header_btn_title VARCHAR(255) DEFAULT NULL, ADD header_btn_url VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE header (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, btn_title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, btn_url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, illustration VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE illustration DROP FOREIGN KEY FK_D67B9A42F6798F43');
        $this->addSql('DROP INDEX IDX_D67B9A42F6798F43 ON illustration');
        $this->addSql('ALTER TABLE illustration CHANGE trottinette_id trotinette_id INT NOT NULL');
        $this->addSql('ALTER TABLE illustration ADD CONSTRAINT FK_D67B9A4264F0FC18 FOREIGN KEY (trotinette_id) REFERENCES trottinette (id)');
        $this->addSql('CREATE INDEX IDX_D67B9A4264F0FC18 ON illustration (trotinette_id)');
        $this->addSql('ALTER TABLE trottinette DROP is_header, DROP header_image, DROP header_btn_title, DROP header_btn_url');
    }
}
