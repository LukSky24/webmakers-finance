<?php

namespace App\Finance\Application\Service;

use App\Core\Domain\Service\WarningGeneratorInterface;
use App\Core\Domain\Entity\Warning;
use App\Core\Domain\Repository\WarningRepositoryInterface;
use App\Core\Domain\ValueObject\ObjectReference;
use App\Core\Domain\ValueObject\WarningType;
use App\Finance\Domain\Repository\BudgetRepositoryInterface;

class BudgetWarningGenerator implements WarningGeneratorInterface
{
    public function __construct(
        private readonly BudgetRepositoryInterface $budgetRepository,
        private readonly WarningRepositoryInterface $warningRepository
    ) {}

    public function generateWarnings(): array
    {
        $results = [
            'added' => 0,
            'maintained' => 0,
            'removed' => 0
        ];

        $negativeBudgets = $this->budgetRepository->findNegative();

        foreach ($negativeBudgets as $budget) {
            $existingWarnings = $this->warningRepository->findByObjectTypeAndId(
                'budget',
                $budget->getId()
            );
            
            $hasNegativeWarning = !empty(array_filter(
                $existingWarnings,
                fn($warning) => $warning->getWarningType() === WarningType::BUDGET_NEGATIVE
            ));

            if (!$hasNegativeWarning) {
                // Add new warning
                $warning = new Warning(
                    new ObjectReference('budget', $budget->getId()),
                    WarningType::BUDGET_NEGATIVE
                );
                $this->warningRepository->save($warning);
                $results['added']++;
            } else {
                // Maintain existing warning
                $results['maintained']++;
            }
        }

        // Check for budgets that are no longer negative
        $allBudgets = $this->budgetRepository->findAll();
        foreach ($allBudgets as $budget) {
            if (!$budget->isNegative()) {
                $existingWarnings = $this->warningRepository->findByObjectTypeAndId(
                    'budget',
                    $budget->getId()
                );
                
                $hasNegativeWarning = !empty(array_filter(
                    $existingWarnings,
                    fn($warning) => $warning->getWarningType() === WarningType::BUDGET_NEGATIVE
                ));

                if ($hasNegativeWarning) {
                    // Remove warning
                    $this->warningRepository->deleteByObjectTypeAndId('budget', $budget->getId());
                    $results['removed']++;
                }
            }
        }

        return $results;
    }

    public function getSupportedObjectType(): string
    {
        return 'budget';
    }
}
