<?php

namespace App\Command;

use App\Core\Application\Service\WarningProcessor;
use App\Core\Domain\ValueObject\TableData;
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
        private readonly WarningProcessor $warningProcessor
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Generating Warnings');

        $summary = $this->warningProcessor->processAll();

        if ($summary->isEmpty()) {
            $io->info('No warnings were generated.');
            return Command::SUCCESS;
        }

        // Display results for each generator
        foreach ($summary->getGeneratorNames() as $generatorName) {
            $io->section("Processing {$generatorName} warnings...");
            
            $result = $summary->getResult($generatorName);
            
            if ($result === null) {
                $io->warning("No result found for generator: {$generatorName}");
                continue;
            }
            
            $tableData = TableData::fromWarningResult("{$generatorName} Results", $result);
            $io->table($tableData->getHeaders(), $tableData->toArray());
        }

        $io->success('Warning generation completed!');
        
        // Display total summary
        $totalResult = $summary->getTotalResult();
        $totalTableData = TableData::fromWarningResult("Total Summary", $totalResult);
        $io->table($totalTableData->getHeaders(), $totalTableData->toArray());

        return Command::SUCCESS;
    }
}
