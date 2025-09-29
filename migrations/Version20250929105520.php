<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929105520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trottinette_caracteristique DROP FOREIGN KEY FK_22FC340C1704EEB7');
        $this->addSql('DROP INDEX IDX_22FC340C1704EEB7 ON trottinette_caracteristique');
        $this->addSql('ALTER TABLE trottinette_caracteristique ADD title VARCHAR(255) NOT NULL, ADD category VARCHAR(255) DEFAULT NULL, DROP caracteristique_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trottinette_caracteristique ADD caracteristique_id INT DEFAULT NULL, DROP title, DROP category');
        $this->addSql('ALTER TABLE trottinette_caracteristique ADD CONSTRAINT FK_22FC340C1704EEB7 FOREIGN KEY (caracteristique_id) REFERENCES caracteristique (id)');
        $this->addSql('CREATE INDEX IDX_22FC340C1704EEB7 ON trottinette_caracteristique (caracteristique_id)');
    }
}
