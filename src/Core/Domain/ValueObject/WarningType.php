<?php

namespace App\Core\Domain\ValueObject;

enum WarningType: string
{
    case CONTRACTOR_OVERDUE_AMOUNT = 'przekroczona suma zaległości kontrahenta';
    case INVOICE_OVERDUE = 'faktura przeterminowana';
    case BUDGET_NEGATIVE = 'budżet poniżej zera';

    public function getValue(): string
    {
        return $this->value;
    }
}
