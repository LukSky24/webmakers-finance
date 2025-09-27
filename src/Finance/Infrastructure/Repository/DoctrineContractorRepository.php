<?php

namespace App\Finance\Infrastructure\Repository;

use App\Finance\Domain\Entity\Contractor;
use App\Finance\Domain\Repository\ContractorRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineContractorRepository implements ContractorRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function save(Contractor $contractor): void
    {
        $this->entityManager->persist($contractor);
        $this->entityManager->flush();
    }

    public function findById(int $id): ?Contractor
    {
        return $this->entityManager->getRepository(Contractor::class)->find($id);
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Contractor::class)->findAll();
    }

    public function findActive(): array
    {
        return $this->entityManager->getRepository(Contractor::class)
            ->createQueryBuilder('c')
            ->where('c.timestamp.deletedAt IS NULL')
            ->getQuery()
            ->getResult();
    }
}
