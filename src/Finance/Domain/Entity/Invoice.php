<?php

namespace App\Finance\Domain\Entity;

use App\Shared\Domain\ValueObject\Timestamp;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'invoices')]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private string $number;

    #[ORM\ManyToOne(targetEntity: Contractor::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Contractor $contractor;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private string $amount;

    #[ORM\Column(type: 'boolean')]
    private bool $isPaid = false;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $dueDate;

    #[ORM\Embedded(class: Timestamp::class)]
    private Timestamp $timestamp;

    public function __construct(
        string $number,
        Contractor $contractor,
        float $amount,
        \DateTimeInterface $dueDate
    ) {
        $this->number = $number;
        $this->contractor = $contractor;
        $this->amount = (string) $amount;
        $this->dueDate = $dueDate;
        $this->timestamp = new Timestamp(
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getContractor(): Contractor
    {
        return $this->contractor;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function isPaid(): bool
    {
        return $this->isPaid;
    }

    public function getDueDate(): \DateTimeInterface
    {
        return $this->dueDate;
    }

    public function getTimestamp(): Timestamp
    {
        return $this->timestamp;
    }

    public function markAsPaid(): void
    {
        $this->isPaid = true;
        $this->timestamp = $this->timestamp->update();
    }

    public function isOverdue(): bool
    {
        return !$this->isPaid && $this->dueDate < new \DateTime();
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
