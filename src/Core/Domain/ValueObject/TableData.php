<?php

namespace App\Core\Domain\ValueObject;

class TableData
{
    /** @var TableRow[] */
    private array $rows = [];

    public function __construct(
        private readonly string $title,
        private readonly array $headers = ['Action', 'Count']
    ) {}

    public function addRow(TableRow $row): void
    {
        $this->rows[] = $row;
    }

    public function addResult(WarningResult $result): void
    {
        $this->addRow(TableRow::added($result->getAdded()));
        $this->addRow(TableRow::maintained($result->getMaintained()));
        $this->addRow(TableRow::removed($result->getRemoved()));
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getRows(): array
    {
        return $this->rows;
    }

    public function toArray(): array
    {
        return array_map(fn(TableRow $row) => $row->toArray(), $this->rows);
    }

    public function isEmpty(): bool
    {
        return empty($this->rows);
    }

    public static function fromWarningResult(string $title, WarningResult $result): self
    {
        $table = new self($title);
        $table->addResult($result);
        return $table;
    }
}
