<?php

namespace App\Finance\Application\Service;

use App\Core\Domain\Service\WarningGeneratorInterface;
use App\Core\Domain\Entity\Warning;
use App\Core\Domain\Repository\WarningRepositoryInterface;
use App\Core\Domain\ValueObject\ObjectReference;
use App\Core\Domain\ValueObject\ObjectType;
use App\Core\Domain\ValueObject\WarningType;
use App\Finance\Domain\Repository\ContractorRepositoryInterface;
use App\Finance\Domain\Repository\InvoiceRepositoryInterface;

class ContractorWarningGenerator implements WarningGeneratorInterface
{
    private const OVERDUE_THRESHOLD = 15000.0;

    public function __construct(
        private readonly ContractorRepositoryInterface $contractorRepository,
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly WarningRepositoryInterface $warningRepository
    ) {}

    public function generateWarnings(): array
    {
        $results = [
            'added' => 0,
            'maintained' => 0,
            'removed' => 0
        ];

        $contractors = $this->contractorRepository->findActive();

        foreach ($contractors as $contractor) {
            $overdueInvoices = $this->invoiceRepository->findUnpaidOverdueByContractor($contractor->getId());
            
            $totalOverdueAmount = array_sum(array_map(fn($invoice) => floatval($invoice->getAmount()), $overdueInvoices));
            
            $existingWarnings = $this->warningRepository->findByObjectTypeAndId(
                'contractor',
                $contractor->getId()
            );
            
            $hasOverdueWarning = !empty(array_filter(
                $existingWarnings,
                fn($warning) => $warning->getWarningType() === WarningType::CONTRACTOR_OVERDUE_AMOUNT
            ));

            if ($totalOverdueAmount > self::OVERDUE_THRESHOLD) {
                if (!$hasOverdueWarning) {
                    // Add new warning
                    $warning = new Warning(
                        new ObjectReference('contractor', $contractor->getId()),
                        WarningType::CONTRACTOR_OVERDUE_AMOUNT
                    );
                    $this->warningRepository->save($warning);
                    $results['added']++;
                } else {
                    // Maintain existing warning
                    $results['maintained']++;
                }
            } elseif ($hasOverdueWarning) {
                // Remove warning
                $this->warningRepository->deleteByObjectTypeAndId('contractor', $contractor->getId());
                $results['removed']++;
            }
        }

        return $results;
    }

    public function getSupportedObjectType(): ObjectType
    {
        return ObjectType::CONTRACTOR;
    }
}
