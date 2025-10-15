<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251015092742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE weight (id INT AUTO_INCREMENT NOT NULL, kg DOUBLE PRECISION NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trottinette ADD weight_id INT DEFAULT NULL, ADD stock INT NOT NULL');
        $this->addSql('ALTER TABLE trottinette ADD CONSTRAINT FK_44559939350035DC FOREIGN KEY (weight_id) REFERENCES weight (id)');
        $this->addSql('CREATE INDEX IDX_44559939350035DC ON trottinette (weight_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trottinette DROP FOREIGN KEY FK_44559939350035DC');
        $this->addSql('DROP TABLE weight');
        $this->addSql('DROP INDEX IDX_44559939350035DC ON trottinette');
        $this->addSql('ALTER TABLE trottinette DROP weight_id, DROP stock');
    }
}
