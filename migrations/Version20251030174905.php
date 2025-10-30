<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251030174905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE kase_item DROP CONSTRAINT fk_25db5db5fcff9a92');
        $this->addSql('DROP INDEX idx_25db5db5fcff9a92');
        $this->addSql('ALTER TABLE kase_item RENAME COLUMN kase_id TO case_id');
        $this->addSql('ALTER TABLE kase_item ADD CONSTRAINT FK_25DB5DB5CF10D4F5 FOREIGN KEY (case_id) REFERENCES kase (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_25DB5DB5CF10D4F5 ON kase_item (case_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE kase_item DROP CONSTRAINT FK_25DB5DB5CF10D4F5');
        $this->addSql('DROP INDEX IDX_25DB5DB5CF10D4F5');
        $this->addSql('ALTER TABLE kase_item RENAME COLUMN case_id TO kase_id');
        $this->addSql('ALTER TABLE kase_item ADD CONSTRAINT fk_25db5db5fcff9a92 FOREIGN KEY (kase_id) REFERENCES kase (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_25db5db5fcff9a92 ON kase_item (kase_id)');
    }
}
