<?php

namespace App\Finance\Domain\Repository;

use App\Finance\Domain\Entity\Budget;

interface BudgetRepositoryInterface
{
    public function save(Budget $budget): void;
    public function findById(int $id): ?Budget;
    public function findAll(): array;
    public function findNegative(): array;
}
