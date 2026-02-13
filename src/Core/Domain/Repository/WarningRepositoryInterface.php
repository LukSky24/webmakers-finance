<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Entity\Warning;

interface WarningRepositoryInterface
{
    public function save(Warning $warning): void;
    public function findById(int $id): ?Warning;
    public function findByObjectTypeAndId(string $objectType, int $objectId): array;
    public function deleteByObjectTypeAndId(string $objectType, int $objectId): void;
    public function findAll(): array;
}
