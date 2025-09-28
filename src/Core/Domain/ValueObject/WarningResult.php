<?php

namespace App\Core\Domain\ValueObject;

class WarningResult
{
    public function __construct(
        private readonly int $added = 0,
        private readonly int $maintained = 0,
        private readonly int $removed = 0
    ) {}

    public function getAdded(): int
    {
        return $this->added;
    }

    public function getMaintained(): int
    {
        return $this->maintained;
    }

    public function getRemoved(): int
    {
        return $this->removed;
    }

    public function getTotal(): int
    {
        return $this->added + $this->maintained + $this->removed;
    }

    public function isEmpty(): bool
    {
        return $this->getTotal() === 0;
    }

    public function toArray(): array
    {
        return [
            'added' => $this->added,
            'maintained' => $this->maintained,
            'removed' => $this->removed
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['added'] ?? 0,
            $data['maintained'] ?? 0,
            $data['removed'] ?? 0
        );
    }

    public function add(WarningResult $other): self
    {
        return new self(
            $this->added + $other->added,
            $this->maintained + $other->maintained,
            $this->removed + $other->removed
        );
    }
}
