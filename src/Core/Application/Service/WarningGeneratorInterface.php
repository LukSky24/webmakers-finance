<?php

namespace App\Core\Application\Service;

interface WarningGeneratorInterface
{
    public function generateWarnings(): array;
    public function getSupportedObjectType(): string;
}
