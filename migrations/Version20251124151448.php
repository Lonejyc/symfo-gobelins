<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251124151448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inventory_item (id SERIAL NOT NULL, owner_id INT NOT NULL, item_id INT NOT NULL, float DOUBLE PRECISION NOT NULL, stat_trak BOOLEAN NOT NULL, acquired_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_55BDEA307E3C61F9 ON inventory_item (owner_id)');
        $this->addSql('CREATE INDEX IDX_55BDEA30126F525E ON inventory_item (item_id)');
        $this->addSql('COMMENT ON COLUMN inventory_item.acquired_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE item (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, rarity VARCHAR(255) NOT NULL, image_url VARCHAR(255) DEFAULT NULL, base_price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE kase (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, image_url VARCHAR(255) DEFAULT NULL, required_tier VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE kase_item (id SERIAL NOT NULL, case_id INT NOT NULL, item_id INT NOT NULL, drop_rate DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_25DB5DB5CF10D4F5 ON kase_item (case_id)');
        $this->addSql('CREATE INDEX IDX_25DB5DB5126F525E ON kase_item (item_id)');
        $this->addSql('CREATE TABLE subscription (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, tier VARCHAR(50) NOT NULL, duration_months INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, balance DOUBLE PRECISION NOT NULL, tier VARCHAR(20) NOT NULL, pseudo VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_subscription (id SERIAL NOT NULL, user_id INT NOT NULL, subscription_id INT NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_230A18D1A76ED395 ON user_subscription (user_id)');
        $this->addSql('CREATE INDEX IDX_230A18D19A1887DC ON user_subscription (subscription_id)');
        $this->addSql('COMMENT ON COLUMN user_subscription.start_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_subscription.end_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE inventory_item ADD CONSTRAINT FK_55BDEA307E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE inventory_item ADD CONSTRAINT FK_55BDEA30126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE kase_item ADD CONSTRAINT FK_25DB5DB5CF10D4F5 FOREIGN KEY (case_id) REFERENCES kase (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE kase_item ADD CONSTRAINT FK_25DB5DB5126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_subscription ADD CONSTRAINT FK_230A18D1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_subscription ADD CONSTRAINT FK_230A18D19A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE inventory_item DROP CONSTRAINT FK_55BDEA307E3C61F9');
        $this->addSql('ALTER TABLE inventory_item DROP CONSTRAINT FK_55BDEA30126F525E');
        $this->addSql('ALTER TABLE kase_item DROP CONSTRAINT FK_25DB5DB5CF10D4F5');
        $this->addSql('ALTER TABLE kase_item DROP CONSTRAINT FK_25DB5DB5126F525E');
        $this->addSql('ALTER TABLE user_subscription DROP CONSTRAINT FK_230A18D1A76ED395');
        $this->addSql('ALTER TABLE user_subscription DROP CONSTRAINT FK_230A18D19A1887DC');
        $this->addSql('DROP TABLE inventory_item');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE kase');
        $this->addSql('DROP TABLE kase_item');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_subscription');
    }
}
