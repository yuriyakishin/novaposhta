<?php

namespace Yu\NovaPoshta\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Класс импортирет города и отделения Новой почты в базу данных
 */
class ImportCommand extends Command
{
    /**
     * @var \Yu\NovaPoshta\Model\Import\CityImport
     */
    private $cityImport;

    /**
     * @var \Yu\NovaPoshta\Model\Import\WarehouseImport
     */
    private $warehouseImport;

    public function __construct(
        \Yu\NovaPoshta\Model\Import\CityImport $cityImport,
        \Yu\NovaPoshta\Model\Import\WarehouseImport $warehouseImport,
        string $name = null
    ) {
        $this->cityImport = $cityImport;
        $this->warehouseImport = $warehouseImport;

        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('novaposhta:data:import');
        $this->setDescription('Import cities and warehouses from Nova Poshta to database.');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Import cities.</info>');
        $this->cityImport->execute(function ($message) use ($output) {
            $output->writeln('<info>' . $message . '</info>');
        });

        $output->writeln('<info></info>');
        $output->writeln('<info>Import warehouses.</info>');
        $this->warehouseImport->execute(function ($message) use ($output) {
            $output->writeln('<info>' . $message . '</info>');
        });
    }
}
