<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251030151647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory_item ADD float DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE inventory_item ADD stat_trak BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE kase ALTER required_tier SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE kase ALTER required_tier DROP NOT NULL');
        $this->addSql('ALTER TABLE inventory_item DROP float');
        $this->addSql('ALTER TABLE inventory_item DROP stat_trak');
    }
}
