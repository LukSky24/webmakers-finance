<?php

namespace App\Shared\Domain\ValueObject;

use DateTimeImmutable;

class Timestamp
{
    public function __construct(
        private readonly DateTimeImmutable $createdAt,
        private readonly DateTimeImmutable $updatedAt,
        private readonly ?DateTimeImmutable $deletedAt = null
    ) {}

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    public function markAsDeleted(): self
    {
        return new self(
            $this->createdAt,
            $this->updatedAt,
            new DateTimeImmutable()
        );
    }

    public function update(): self
    {
        return new self(
            $this->createdAt,
            new DateTimeImmutable(),
            $this->deletedAt
        );
    }
}
