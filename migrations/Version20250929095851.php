<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929095851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trottinette_accessory DROP FOREIGN KEY FK_B37F755EF6798F43');
        $this->addSql('ALTER TABLE trottinette_accessory DROP FOREIGN KEY FK_B37F755E27E8CC78');
        $this->addSql('ALTER TABLE trottinette_accessory ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE trottinette_accessory ADD CONSTRAINT FK_B37F755EF6798F43 FOREIGN KEY (trottinette_id) REFERENCES trottinette (id)');
        $this->addSql('ALTER TABLE trottinette_accessory ADD CONSTRAINT FK_B37F755E27E8CC78 FOREIGN KEY (accessory_id) REFERENCES accessory (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trottinette_accessory MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE trottinette_accessory DROP FOREIGN KEY FK_B37F755EF6798F43');
        $this->addSql('ALTER TABLE trottinette_accessory DROP FOREIGN KEY FK_B37F755E27E8CC78');
        $this->addSql('DROP INDEX `PRIMARY` ON trottinette_accessory');
        $this->addSql('ALTER TABLE trottinette_accessory DROP id');
        $this->addSql('ALTER TABLE trottinette_accessory ADD CONSTRAINT FK_B37F755EF6798F43 FOREIGN KEY (trottinette_id) REFERENCES trottinette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trottinette_accessory ADD CONSTRAINT FK_B37F755E27E8CC78 FOREIGN KEY (accessory_id) REFERENCES accessory (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trottinette_accessory ADD PRIMARY KEY (trottinette_id, accessory_id)');
    }
}
