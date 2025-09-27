<?php

namespace App\Finance\Domain\Repository;

use App\Finance\Domain\Entity\Invoice;

interface InvoiceRepositoryInterface
{
    public function save(Invoice $invoice): void;
    public function findById(int $id): ?Invoice;
    public function findByContractorId(int $contractorId): array;
    public function findUnpaidOverdue(): array;
    public function findUnpaidOverdueByContractor(int $contractorId): array;
}
