<?php

namespace App\Core\Application\Service;

use App\Core\Domain\Service\WarningGeneratorInterface;
use App\Core\Domain\ValueObject\WarningResult;
use App\Core\Domain\ValueObject\WarningSummary;

class WarningProcessor
{
    /** @var array<string, WarningGeneratorInterface> */
    private array $generators = [];

    public function __construct(
        WarningGeneratorInterface ...$generators
    ) {
        foreach ($generators as $generator) {
            $this->generators[$generator->getSupportedObjectType()] = $generator;
        }
    }

    public function processAll(): WarningSummary
    {
        $summary = new WarningSummary();

        foreach ($this->generators as $name => $generator) {
            $result = $this->processGenerator($name, $generator);
            $summary->addResult($name, $result);
        }

        return $summary;
    }

    public function processGenerator(string $name, WarningGeneratorInterface $generator): WarningResult
    {
        $arrayResult = $generator->generateWarnings();
        return WarningResult::fromArray($arrayResult);
    }

    public function getSupportedGenerators(): array
    {
        return array_keys($this->generators);
    }

    public function hasGenerator(string $name): bool
    {
        return isset($this->generators[$name]);
    }

    public function getGenerator(string $name): ?WarningGeneratorInterface
    {
        return $this->generators[$name] ?? null;
    }
}
