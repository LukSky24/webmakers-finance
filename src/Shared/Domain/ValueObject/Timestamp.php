<?php

namespace App\Shared\Domain\ValueObject;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Timestamp
{
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    public function __construct(
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
        ?DateTimeImmutable $deletedAt = null
    ) {
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }

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
