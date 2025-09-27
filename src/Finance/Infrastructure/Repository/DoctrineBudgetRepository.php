<?php

namespace App\Finance\Infrastructure\Repository;

use App\Finance\Domain\Entity\Budget;
use App\Finance\Domain\Repository\BudgetRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineBudgetRepository implements BudgetRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function save(Budget $budget): void
    {
        $this->entityManager->persist($budget);
        $this->entityManager->flush();
    }

    public function findById(int $id): ?Budget
    {
        return $this->entityManager->getRepository(Budget::class)->find($id);
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Budget::class)->findAll();
    }

    public function findNegative(): array
    {
        return $this->entityManager->getRepository(Budget::class)
            ->createQueryBuilder('b')
            ->where('b.currentBalance < 0')
            ->andWhere('b.timestamp.deletedAt IS NULL')
            ->getQuery()
            ->getResult();
    }
}
