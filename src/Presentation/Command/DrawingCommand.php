<?php

namespace App\Presentation\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Application\Service\DrawingAppService;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:draw',
    description: 'Drawing tool that processes canvas commands'
)]
class DrawingCommand extends Command
{
    private DrawingAppService $drawingService;

    public function __construct(DrawingAppService $drawingService)
    {
        parent::__construct();
        $this->drawingService = $drawingService;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('input', InputArgument::REQUIRED, 'Path to input file with commands')
            ->addArgument('output', InputArgument::REQUIRED, 'Path to output file for results');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputFile = $input->getArgument('input');
        $outputFile = $input->getArgument('output');

        if (!file_exists($inputFile)) {
            $output->writeln('<error>Error: Input file not found</error>');
            return Command::FAILURE;
        }

        $commands = file($inputFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        try {
            $results = $this->drawingService->executeCommands($commands);
            file_put_contents($outputFile, implode("\n", $results));
            $output->writeln('<info>Drawing completed successfully!</info>');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Error: '.$e->getMessage().'</error>');
            return Command::FAILURE;
        }
    }
}