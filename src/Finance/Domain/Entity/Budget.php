<?php

namespace App\Finance\Domain\Entity;

use App\Shared\Domain\ValueObject\Timestamp;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'budgets')]
class Budget
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $currentBalance;

    #[ORM\Embedded(class: Timestamp::class)]
    private Timestamp $timestamp;

    public function __construct(string $name, float $initialBalance = 0.0)
    {
        $this->name = $name;
        $this->currentBalance = $initialBalance;
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

    public function getCurrentBalance(): float
    {
        return $this->currentBalance;
    }

    public function getTimestamp(): Timestamp
    {
        return $this->timestamp;
    }

    public function addAmount(float $amount): void
    {
        $this->currentBalance += $amount;
        $this->timestamp = $this->timestamp->update();
    }

    public function subtractAmount(float $amount): void
    {
        $this->currentBalance -= $amount;
        $this->timestamp = $this->timestamp->update();
    }

    public function isNegative(): bool
    {
        return $this->currentBalance < 0;
    }

    public function updateName(string $name): void
    {
        $this->name = $name;
        $this->timestamp = $this->timestamp->update();
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
