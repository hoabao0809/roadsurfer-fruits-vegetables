<?php

namespace App\Command;

use App\Service\FoodCollectionProcessor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:process-food',
    description: 'Add a short description for your command',
)]
class ImportFoodJsonCommand extends Command
{
    private FoodCollectionProcessor $foodCollectionProcessor;

    public function __construct(FoodCollectionProcessor $foodCollectionProcessor)
    {
        parent::__construct();
        $this->foodCollectionProcessor = $foodCollectionProcessor;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = 'var/data/request.json';
        
        $this->foodCollectionProcessor->process($filePath);

        $output->writeln('Food collections processed and stored.');

        return Command::SUCCESS;
    }
}
