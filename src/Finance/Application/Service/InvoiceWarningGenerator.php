<?php

namespace App\Finance\Application\Service;

use App\Core\Domain\Service\WarningGeneratorInterface;
use App\Core\Domain\Entity\Warning;
use App\Core\Domain\Repository\WarningRepositoryInterface;
use App\Core\Domain\ValueObject\ObjectReference;
use App\Core\Domain\ValueObject\ObjectType;
use App\Core\Domain\ValueObject\WarningType;
use App\Finance\Domain\Repository\InvoiceRepositoryInterface;

class InvoiceWarningGenerator implements WarningGeneratorInterface
{
    public function __construct(
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

        $overdueInvoices = $this->invoiceRepository->findUnpaidOverdue();

        foreach ($overdueInvoices as $invoice) {
            $existingWarnings = $this->warningRepository->findByObjectTypeAndId(
                'invoice',
                $invoice->getId()
            );
            
            $hasOverdueWarning = !empty(array_filter(
                $existingWarnings,
                fn($warning) => $warning->getWarningType() === WarningType::INVOICE_OVERDUE
            ));

            if (!$hasOverdueWarning) {
                // Add new warning
                $warning = new Warning(
                    new ObjectReference('invoice', $invoice->getId()),
                    WarningType::INVOICE_OVERDUE
                );
                $this->warningRepository->save($warning);
                $results['added']++;
            } else {
                // Maintain existing warning
                $results['maintained']++;
            }
        }

        // Check for invoices that are no longer overdue (paid or due date changed)
        $allInvoices = $this->invoiceRepository->findAll();
        foreach ($allInvoices as $invoice) {
            if (!$invoice->isOverdue()) {
                $existingWarnings = $this->warningRepository->findByObjectTypeAndId(
                    'invoice',
                    $invoice->getId()
                );
                
                $hasOverdueWarning = !empty(array_filter(
                    $existingWarnings,
                    fn($warning) => $warning->getWarningType() === WarningType::INVOICE_OVERDUE
                ));

                if ($hasOverdueWarning) {
                    // Remove warning
                    $this->warningRepository->deleteByObjectTypeAndId('invoice', $invoice->getId());
                    $results['removed']++;
                }
            }
        }

        return $results;
    }

    public function getSupportedObjectType(): ObjectType
    {
        return ObjectType::INVOICE;
    }
}
