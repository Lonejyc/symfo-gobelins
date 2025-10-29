<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251029104936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, rarity VARCHAR(255) NOT NULL, image_url VARCHAR(255) DEFAULT NULL, market_price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE kase (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, image_url VARCHAR(255) DEFAULT NULL, required_tier VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE kase_item (id SERIAL NOT NULL, kase_id INT NOT NULL, item_id INT NOT NULL, drop_rate DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_25DB5DB5FCFF9A92 ON kase_item (kase_id)');
        $this->addSql('CREATE INDEX IDX_25DB5DB5126F525E ON kase_item (item_id)');
        $this->addSql('ALTER TABLE kase_item ADD CONSTRAINT FK_25DB5DB5FCFF9A92 FOREIGN KEY (kase_id) REFERENCES kase (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE kase_item ADD CONSTRAINT FK_25DB5DB5126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD tier VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE kase_item DROP CONSTRAINT FK_25DB5DB5FCFF9A92');
        $this->addSql('ALTER TABLE kase_item DROP CONSTRAINT FK_25DB5DB5126F525E');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE kase');
        $this->addSql('DROP TABLE kase_item');
        $this->addSql('ALTER TABLE "user" DROP tier');
    }
}
