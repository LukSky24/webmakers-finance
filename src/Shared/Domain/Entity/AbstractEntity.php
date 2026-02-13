<?php

namespace App\Shared\Domain\Entity;

use App\Shared\Domain\ValueObject\Timestamp;

abstract class AbstractEntity
{
    protected Timestamp $timestamp;

    protected function __construct()
    {
        $this->timestamp = new Timestamp(
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );
    }

    public function getTimestamp(): Timestamp
    {
        return $this->timestamp;
    }

    public function isDeleted(): bool
    {
        return $this->timestamp->isDeleted();
    }

    public function markAsDeleted(): void
    {
        $this->timestamp = $this->timestamp->markAsDeleted();
    }

    protected function updateTimestamp(): void
    {
        $this->timestamp = $this->timestamp->update();
    }
}
