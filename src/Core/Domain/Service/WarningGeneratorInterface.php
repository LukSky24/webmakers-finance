<?php

namespace App\Core\Domain\Service;

use App\Core\Domain\ValueObject\ObjectType;

interface WarningGeneratorInterface
{
    public function generateWarnings(): array;
    public function getSupportedObjectType(): ObjectType;
}
