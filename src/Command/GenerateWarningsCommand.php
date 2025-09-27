<?php

namespace App\Command;

use App\Core\Application\Service\WarningGeneratorInterface;
use App\Finance\Application\Service\BudgetWarningGenerator;
use App\Finance\Application\Service\ContractorWarningGenerator;
use App\Finance\Application\Service\InvoiceWarningGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:warnings:generate',
    description: 'Generate warnings based on business rules'
)]
class GenerateWarningsCommand extends Command
{
    public function __construct(
        private readonly ContractorWarningGenerator $contractorWarningGenerator,
        private readonly InvoiceWarningGenerator $invoiceWarningGenerator,
        private readonly BudgetWarningGenerator $budgetWarningGenerator
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Generating Warnings');

        $generators = [
            'Contractor' => $this->contractorWarningGenerator,
            'Invoice' => $this->invoiceWarningGenerator,
            'Budget' => $this->budgetWarningGenerator,
        ];

        $totalResults = [
            'added' => 0,
            'maintained' => 0,
            'removed' => 0
        ];

        foreach ($generators as $type => $generator) {
            $io->section("Processing {$type} warnings...");
            
            $results = $generator->generateWarnings();
            
            $io->table(
                ['Action', 'Count'],
                [
                    ['Added', $results['added']],
                    ['Maintained', $results['maintained']],
                    ['Removed', $results['removed']],
                ]
            );

            $totalResults['added'] += $results['added'];
            $totalResults['maintained'] += $results['maintained'];
            $totalResults['removed'] += $results['removed'];
        }

        $io->success('Warning generation completed!');
        
        $io->table(
            ['Total Action', 'Count'],
            [
                ['Total Added', $totalResults['added']],
                ['Total Maintained', $totalResults['maintained']],
                ['Total Removed', $totalResults['removed']],
            ]
        );

        return Command::SUCCESS;
    }
}
