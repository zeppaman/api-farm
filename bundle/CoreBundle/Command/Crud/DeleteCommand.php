<?php

namespace Apifarm\CoreBundle\Command\Crud;

use Apifarm\CoreBundle\Services\ICrudService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'apifarm:crud:delete';

    /** var  $service ICrudService */
    private  $service;
    public function __construct(ICrudService $service)
    {
        $this->service=$service;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this
        // configure an argument
        ->addArgument('database', InputArgument::REQUIRED, 'Database name')
        ->addArgument('collection', InputArgument::REQUIRED, 'Collection name')
        ->addArgument('id', InputArgument::REQUIRED, 'id to remove');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $db=$input->getArgument('database');
        $collection=$input->getArgument('collection');
        $id=$input->getArgument('id');

        $output->writeln("Deleting db: $db collection: $collection id: $id");
        $this->service->delete($db,$collection,$id);        

        return Command::SUCCESS;

        // return Command::FAILURE;
    }
}