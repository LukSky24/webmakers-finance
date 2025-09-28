<?php

namespace App\Core\Domain\Service;

interface WarningGeneratorInterface
{
    public function generateWarnings(): array;
    public function getSupportedObjectType(): string;
}
