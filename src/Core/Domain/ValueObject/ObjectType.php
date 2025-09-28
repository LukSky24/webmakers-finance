<?php

namespace App\Core\Domain\ValueObject;

enum ObjectType: string
{
    case BUDGET = 'budget';
    case INVOICE = 'invoice';
    case CONTRACTOR = 'contractor';

    public function getValue(): string
    {
        return $this->value;
    }
}
