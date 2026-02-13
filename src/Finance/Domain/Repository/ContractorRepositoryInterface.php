<?php

namespace App\Finance\Domain\Repository;

use App\Finance\Domain\Entity\Contractor;

interface ContractorRepositoryInterface
{
    public function save(Contractor $contractor): void;
    public function findById(int $id): ?Contractor;
    public function findAll(): array;
    public function findActive(): array;
}
