<?php

namespace App\Core\Domain\ValueObject;

class TableRow
{
    public function __construct(
        private readonly string $action,
        private readonly int $count
    ) {}

    public function getAction(): string
    {
        return $this->action;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function toArray(): array
    {
        return [$this->action, $this->count];
    }

    public static function added(int $count): self
    {
        return new self('Added', $count);
    }

    public static function maintained(int $count): self
    {
        return new self('Maintained', $count);
    }

    public static function removed(int $count): self
    {
        return new self('Removed', $count);
    }
}
