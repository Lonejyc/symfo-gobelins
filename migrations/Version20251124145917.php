<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251124145917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscription (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, tier VARCHAR(50) NOT NULL, duration_months INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_subscription (id SERIAL NOT NULL, user_id INT NOT NULL, subscription_id INT NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_230A18D1A76ED395 ON user_subscription (user_id)');
        $this->addSql('CREATE INDEX IDX_230A18D19A1887DC ON user_subscription (subscription_id)');
        $this->addSql('COMMENT ON COLUMN user_subscription.start_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_subscription.end_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user_subscription ADD CONSTRAINT FK_230A18D1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_subscription ADD CONSTRAINT FK_230A18D19A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_subscription DROP CONSTRAINT FK_230A18D1A76ED395');
        $this->addSql('ALTER TABLE user_subscription DROP CONSTRAINT FK_230A18D19A1887DC');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE user_subscription');
    }
}
