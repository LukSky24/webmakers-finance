<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Entity\Warning;
use App\Core\Domain\Repository\WarningRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineWarningRepository implements WarningRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function save(Warning $warning): void
    {
        $this->entityManager->persist($warning);
        $this->entityManager->flush();
    }

    public function findById(int $id): ?Warning
    {
        return $this->entityManager->getRepository(Warning::class)->find($id);
    }

    public function findByObjectTypeAndId(string $objectType, int $objectId): array
    {
        return $this->entityManager->getRepository(Warning::class)
            ->createQueryBuilder('w')
            ->where('w.objectType = :objectType')
            ->andWhere('w.objectId = :objectId')
            ->andWhere('w.timestamp.deletedAt IS NULL')
            ->setParameter('objectType', $objectType)
            ->setParameter('objectId', $objectId)
            ->getQuery()
            ->getResult();
    }

    public function deleteByObjectTypeAndId(string $objectType, int $objectId): void
    {
        $warnings = $this->findByObjectTypeAndId($objectType, $objectId);

        foreach ($warnings as $warning) {
            $warning->markAsDeleted();
            $this->entityManager->persist($warning);
        }

        $this->entityManager->flush();
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Warning::class)->findAll();
    }
}
