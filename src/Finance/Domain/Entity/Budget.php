<?php

namespace App\Finance\Domain\Entity;

use App\Shared\Domain\Entity\AbstractEntity;
use App\Shared\Domain\ValueObject\Timestamp;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'budgets')]
class Budget extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private string $currentBalance;

    #[ORM\Embedded(class: Timestamp::class)]
    protected Timestamp $timestamp;

    public function __construct(string $name, float $initialBalance = 0.0)
    {
        parent::__construct();
        $this->name = $name;
        $this->currentBalance = (string) $initialBalance;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCurrentBalance(): string
    {
        return $this->currentBalance;
    }

    public function addAmount(float $amount): void
    {
        $this->currentBalance = (string) (floatval($this->currentBalance) + $amount);
        $this->updateTimestamp();
    }

    public function subtractAmount(float $amount): void
    {
        $this->currentBalance = (string) (floatval($this->currentBalance) - $amount);
        $this->updateTimestamp();
    }

    public function isNegative(): bool
    {
        return floatval($this->currentBalance) < 0;
    }

    public function updateName(string $name): void
    {
        $this->name = $name;
        $this->updateTimestamp();
    }
}
