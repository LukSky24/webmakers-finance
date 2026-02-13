<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250927090136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE budgets (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, current_balance NUMERIC(10, 2) NOT NULL, timestamp_created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, timestamp_updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, timestamp_deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN budgets.timestamp_created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN budgets.timestamp_updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN budgets.timestamp_deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE contractors (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, timestamp_created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, timestamp_updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, timestamp_deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN contractors.timestamp_created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN contractors.timestamp_updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN contractors.timestamp_deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE invoices (id SERIAL NOT NULL, contractor_id INT NOT NULL, number VARCHAR(100) NOT NULL, amount NUMERIC(10, 2) NOT NULL, is_paid BOOLEAN NOT NULL, due_date DATE NOT NULL, timestamp_created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, timestamp_updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, timestamp_deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A2F2F9596901F54 ON invoices (number)');
        $this->addSql('CREATE INDEX IDX_6A2F2F95B0265DC7 ON invoices (contractor_id)');
        $this->addSql('COMMENT ON COLUMN invoices.timestamp_created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN invoices.timestamp_updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN invoices.timestamp_deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE warnings (id SERIAL NOT NULL, object_type VARCHAR(50) NOT NULL, object_id INT NOT NULL, warning_type VARCHAR(100) NOT NULL, timestamp_created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, timestamp_updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, timestamp_deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN warnings.timestamp_created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN warnings.timestamp_updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN warnings.timestamp_deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F95B0265DC7 FOREIGN KEY (contractor_id) REFERENCES contractors (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invoices DROP CONSTRAINT FK_6A2F2F95B0265DC7');
        $this->addSql('DROP TABLE budgets');
        $this->addSql('DROP TABLE contractors');
        $this->addSql('DROP TABLE invoices');
        $this->addSql('DROP TABLE warnings');
    }
}
