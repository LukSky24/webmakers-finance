<?php

namespace App\Core\Domain\ValueObject;

class WarningSummary
{
    /** @var array<string, WarningResult> */
    private array $results = [];

    public function addResult(string $generatorName, WarningResult $result): void
    {
        $this->results[$generatorName] = $result;
    }

    public function getResult(string $generatorName): ?WarningResult
    {
        return $this->results[$generatorName] ?? null;
    }

    public function getTotalResult(): WarningResult
    {
        $total = new WarningResult();
        
        foreach ($this->results as $result) {
            $total = $total->add($result);
        }
        
        return $total;
    }

    public function getGeneratorNames(): array
    {
        return array_keys($this->results);
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function isEmpty(): bool
    {
        return empty($this->results) || $this->getTotalResult()->isEmpty();
    }

    public function getGeneratorCount(): int
    {
        return count($this->results);
    }

    public function toArray(): array
    {
        $array = [];
        foreach ($this->results as $name => $result) {
            $array[$name] = $result->toArray();
        }
        return $array;
    }
}
