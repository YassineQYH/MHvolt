<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251223133302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD350035DC');
        $this->addSql('DROP INDEX IDX_D34A04AD350035DC ON product');
        $this->addSql('ALTER TABLE product ADD weight DOUBLE PRECISION NOT NULL, DROP weight_id, DROP manual_weight');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD weight_id INT NOT NULL, ADD manual_weight DOUBLE PRECISION DEFAULT NULL, DROP weight');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD350035DC FOREIGN KEY (weight_id) REFERENCES weight (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D34A04AD350035DC ON product (weight_id)');
    }
}
