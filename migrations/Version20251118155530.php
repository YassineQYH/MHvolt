<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251118155530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trottinette_accessory ADD CONSTRAINT FK_B37F755EF6798F43 FOREIGN KEY (trottinette_id) REFERENCES trottinette (id)');
        $this->addSql('ALTER TABLE trottinette_accessory ADD CONSTRAINT FK_B37F755E27E8CC78 FOREIGN KEY (accessory_id) REFERENCES product (id)');
        $this->addSql('DROP INDEX fk_trottinette ON trottinette_accessory');
        $this->addSql('CREATE INDEX IDX_B37F755EF6798F43 ON trottinette_accessory (trottinette_id)');
        $this->addSql('DROP INDEX fk_accessory ON trottinette_accessory');
        $this->addSql('CREATE INDEX IDX_B37F755E27E8CC78 ON trottinette_accessory (accessory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trottinette_accessory DROP FOREIGN KEY FK_B37F755EF6798F43');
        $this->addSql('ALTER TABLE trottinette_accessory DROP FOREIGN KEY FK_B37F755E27E8CC78');
        $this->addSql('ALTER TABLE trottinette_accessory DROP FOREIGN KEY FK_B37F755EF6798F43');
        $this->addSql('ALTER TABLE trottinette_accessory DROP FOREIGN KEY FK_B37F755E27E8CC78');
        $this->addSql('DROP INDEX idx_b37f755ef6798f43 ON trottinette_accessory');
        $this->addSql('CREATE INDEX FK_TROTTINETTE ON trottinette_accessory (trottinette_id)');
        $this->addSql('DROP INDEX idx_b37f755e27e8cc78 ON trottinette_accessory');
        $this->addSql('CREATE INDEX FK_ACCESSORY ON trottinette_accessory (accessory_id)');
        $this->addSql('ALTER TABLE trottinette_accessory ADD CONSTRAINT FK_B37F755EF6798F43 FOREIGN KEY (trottinette_id) REFERENCES trottinette (id)');
        $this->addSql('ALTER TABLE trottinette_accessory ADD CONSTRAINT FK_B37F755E27E8CC78 FOREIGN KEY (accessory_id) REFERENCES product (id)');
    }
}
