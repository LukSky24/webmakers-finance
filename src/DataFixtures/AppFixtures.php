<?php

namespace App\DataFixtures;

use App\Core\Domain\Entity\Warning;
use App\Core\Domain\ValueObject\ObjectReference;
use App\Core\Domain\ValueObject\WarningType;
use App\Finance\Domain\Entity\Budget;
use App\Finance\Domain\Entity\Contractor;
use App\Finance\Domain\Entity\Invoice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Contractors
        $contractor1 = new Contractor('ABC Sp. z o.o.');
        $contractor2 = new Contractor('XYZ S.A.');
        $contractor3 = new Contractor('DEF Ltd.');
        $contractor4 = new Contractor('GHI Sp. j.');
        
        $manager->persist($contractor1);
        $manager->persist($contractor2);
        $manager->persist($contractor3);
        $manager->persist($contractor4);
        $manager->flush();

        // Budgets
        $budget1 = new Budget('Budżet główny', 50000.00);
        $budget2 = new Budget('Budżet marketingowy', -5000.00); // Negative budget - should trigger warning
        $budget3 = new Budget('Budżet IT', 15000.00);
        $budget4 = new Budget('Budżet sprzedaży', -2500.00); // Another negative budget
        
        $manager->persist($budget1);
        $manager->persist($budget2);
        $manager->persist($budget3);
        $manager->persist($budget4);
        $manager->flush();

        // Invoices - different scenarios
        $now = new \DateTime();
        $overdueDate = (clone $now)->modify('-10 days');
        $futureDate = (clone $now)->modify('+30 days');
        $recentOverdueDate = (clone $now)->modify('-5 days');

        // Contractor 1 - should trigger contractor warning (total overdue: 5000 + 8000 = 13000, but let's add more)
        $invoice1 = new Invoice('INV-001', $contractor1, 5000.00, $overdueDate);
        $invoice2 = new Invoice('INV-002', $contractor1, 8000.00, $overdueDate);
        $invoice3 = new Invoice('INV-003', $contractor1, 3000.00, $overdueDate); // Total: 16000 > 15000 threshold
        
        // Contractor 2 - should trigger contractor warning (total overdue: 12000, but let's make it more)
        $invoice4 = new Invoice('INV-004', $contractor2, 12000.00, $overdueDate);
        $invoice5 = new Invoice('INV-005', $contractor2, 5000.00, $recentOverdueDate); // Total: 17000 > 15000 threshold
        
        // Contractor 3 - paid invoice (should not trigger warning)
        $invoice6 = new Invoice('INV-006', $contractor3, 3000.00, $overdueDate);
        $invoice6->markAsPaid();
        
        // Contractor 4 - future invoice (should not trigger warning)
        $invoice7 = new Invoice('INV-007', $contractor4, 2000.00, $futureDate);
        
        // Additional overdue invoices for contractor 1 to exceed threshold
        $invoice8 = new Invoice('INV-008', $contractor1, 2000.00, $overdueDate); // Total now: 18000
        
        $manager->persist($invoice1);
        $manager->persist($invoice2);
        $manager->persist($invoice3);
        $manager->persist($invoice4);
        $manager->persist($invoice5);
        $manager->persist($invoice6);
        $manager->persist($invoice7);
        $manager->persist($invoice8);
        $manager->flush();

        // Some existing warnings (to test maintenance and removal logic)
        $warning1 = new Warning(
            new ObjectReference('budget', $budget2->getId()),
            WarningType::BUDGET_NEGATIVE
        );
        
        $warning2 = new Warning(
            new ObjectReference('invoice', $invoice1->getId()),
            WarningType::INVOICE_OVERDUE
        );
        
        // This warning should be removed when we run the generator (invoice6 is paid)
        $warning3 = new Warning(
            new ObjectReference('invoice', $invoice6->getId()),
            WarningType::INVOICE_OVERDUE
        );
        
        $manager->persist($warning1);
        $manager->persist($warning2);
        $manager->persist($warning3);
        $manager->flush();
    }
}