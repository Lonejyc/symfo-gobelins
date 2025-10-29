<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251029153002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inventory_item (id SERIAL NOT NULL, owner_id INT NOT NULL, item_id INT NOT NULL, acquired_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_55BDEA307E3C61F9 ON inventory_item (owner_id)');
        $this->addSql('CREATE INDEX IDX_55BDEA30126F525E ON inventory_item (item_id)');
        $this->addSql('COMMENT ON COLUMN inventory_item.acquired_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE inventory_item ADD CONSTRAINT FK_55BDEA307E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE inventory_item ADD CONSTRAINT FK_55BDEA30126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER email TYPE VARCHAR(180)');
        $this->addSql('ALTER TABLE "user" ALTER pseudo SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER pseudo TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE inventory_item DROP CONSTRAINT FK_55BDEA307E3C61F9');
        $this->addSql('ALTER TABLE inventory_item DROP CONSTRAINT FK_55BDEA30126F525E');
        $this->addSql('DROP TABLE inventory_item');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL');
        $this->addSql('ALTER TABLE "user" DROP created_at');
        $this->addSql('ALTER TABLE "user" ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE "user" ALTER pseudo DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER pseudo TYPE VARCHAR(80)');
    }
}
