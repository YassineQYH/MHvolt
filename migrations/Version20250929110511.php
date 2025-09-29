<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929110511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_caracteristique (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trottinette_caracteristique ADD caracteristique_id INT DEFAULT NULL, ADD categorie_id INT DEFAULT NULL, DROP category, CHANGE title title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE trottinette_caracteristique ADD CONSTRAINT FK_22FC340C1704EEB7 FOREIGN KEY (caracteristique_id) REFERENCES caracteristique (id)');
        $this->addSql('ALTER TABLE trottinette_caracteristique ADD CONSTRAINT FK_22FC340CBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_caracteristique (id)');
        $this->addSql('CREATE INDEX IDX_22FC340C1704EEB7 ON trottinette_caracteristique (caracteristique_id)');
        $this->addSql('CREATE INDEX IDX_22FC340CBCF5E72D ON trottinette_caracteristique (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trottinette_caracteristique DROP FOREIGN KEY FK_22FC340CBCF5E72D');
        $this->addSql('DROP TABLE categorie_caracteristique');
        $this->addSql('ALTER TABLE trottinette_caracteristique DROP FOREIGN KEY FK_22FC340C1704EEB7');
        $this->addSql('DROP INDEX IDX_22FC340C1704EEB7 ON trottinette_caracteristique');
        $this->addSql('DROP INDEX IDX_22FC340CBCF5E72D ON trottinette_caracteristique');
        $this->addSql('ALTER TABLE trottinette_caracteristique ADD category VARCHAR(255) DEFAULT NULL, DROP caracteristique_id, DROP categorie_id, CHANGE title title VARCHAR(255) NOT NULL');
    }
}
