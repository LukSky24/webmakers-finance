<?php

namespace App\Core\Domain\ValueObject;

enum WarningType: string
{
    case CONTRACTOR_OVERDUE_AMOUNT = 'contractor_overdue_amount';
    case INVOICE_OVERDUE = 'invoice_overdue';
    case BUDGET_NEGATIVE = 'budget_negative';

    public function getValue(): string
    {
        return $this->value;
    }
}
