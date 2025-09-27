<?php

namespace App\Finance\Infrastructure\Repository;

use App\Finance\Domain\Entity\Invoice;
use App\Finance\Domain\Repository\InvoiceRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineInvoiceRepository implements InvoiceRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function save(Invoice $invoice): void
    {
        $this->entityManager->persist($invoice);
        $this->entityManager->flush();
    }

    public function findById(int $id): ?Invoice
    {
        return $this->entityManager->getRepository(Invoice::class)->find($id);
    }

    public function findByContractorId(int $contractorId): array
    {
        return $this->entityManager->getRepository(Invoice::class)
            ->createQueryBuilder('i')
            ->where('i.contractor = :contractorId')
            ->andWhere('i.timestamp.deletedAt IS NULL')
            ->setParameter('contractorId', $contractorId)
            ->getQuery()
            ->getResult();
    }

    public function findUnpaidOverdue(): array
    {
        $now = new \DateTime();

        return $this->entityManager->getRepository(Invoice::class)
            ->createQueryBuilder('i')
            ->where('i.isPaid = false')
            ->andWhere('i.dueDate < :now')
            ->andWhere('i.timestamp.deletedAt IS NULL')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
    }

    public function findUnpaidOverdueByContractor(int $contractorId): array
    {
        $now = new \DateTime();

        return $this->entityManager->getRepository(Invoice::class)
            ->createQueryBuilder('i')
            ->where('i.contractor = :contractorId')
            ->andWhere('i.isPaid = false')
            ->andWhere('i.dueDate < :now')
            ->andWhere('i.timestamp.deletedAt IS NULL')
            ->setParameter('contractorId', $contractorId)
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
    }
}
