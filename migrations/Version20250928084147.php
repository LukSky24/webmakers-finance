<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250928084147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update warning_type values from Polish to English';
    }

    public function up(Schema $schema): void
    {
        // Update existing warning_type values from Polish to English
        $this->addSql("UPDATE warnings SET warning_type = 'contractor_overdue_amount' WHERE warning_type = 'przekroczona suma zaległości kontrahenta'");
        $this->addSql("UPDATE warnings SET warning_type = 'invoice_overdue' WHERE warning_type = 'faktura przeterminowana'");
        $this->addSql("UPDATE warnings SET warning_type = 'budget_negative' WHERE warning_type = 'budżet poniżej zera'");
    }

    public function down(Schema $schema): void
    {
        // Revert warning_type values from English to Polish
        $this->addSql("UPDATE warnings SET warning_type = 'przekroczona suma zaległości kontrahenta' WHERE warning_type = 'contractor_overdue_amount'");
        $this->addSql("UPDATE warnings SET warning_type = 'faktura przeterminowana' WHERE warning_type = 'invoice_overdue'");
        $this->addSql("UPDATE warnings SET warning_type = 'budżet poniżej zera' WHERE warning_type = 'budget_negative'");
    }
}
