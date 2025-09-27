<?php

namespace App\Finance\Domain\Entity;

use App\Shared\Domain\ValueObject\Timestamp;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'contractors')]
class Contractor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Embedded(class: Timestamp::class)]
    private Timestamp $timestamp;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->timestamp = new Timestamp(
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function updateName(string $name): void
    {
        $this->name = $name;
        $this->timestamp = $this->timestamp->update();
    }

    public function getTimestamp(): Timestamp
    {
        return $this->timestamp;
    }

    public function markAsDeleted(): void
    {
        $this->timestamp = $this->timestamp->markAsDeleted();
    }

    public function isDeleted(): bool
    {
        return $this->timestamp->isDeleted();
    }
}
