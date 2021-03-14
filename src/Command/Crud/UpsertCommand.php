<?php

namespace App\Command\Crud;

use App\Services\ICrudService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpsertCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'apifarm:crud:upsert';

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
        ->addArgument('item', InputArgument::REQUIRED, 'Collection name')
        ->addArgument('replace', InputArgument::OPTIONAL, 'Replace in case of update',true);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $db=$input->getArgument('database');
        $collection=$input->getArgument('collection');
        $json=$input->getArgument('item');
        $replace=$input->getArgument('replace');
        $output->writeln("Userting db: $db collection: $collection replace: $replace data: $json");

        $item=json_decode($json,true);

        $output->writeln("Parsed item:".print_r($item,true));

        $id=null;
        if(key_exists("_id",$item))
        {       
            $output->writeln("Updating db: $db collection: $collection id: $id");
            $this->service->update($db,$collection,$item, $replace);
        }
        else
        {
            $output->writeln("Adding db: $db collection: $collection id: $id");
            $this->service->add($db,$collection,$item);
        }

        return Command::SUCCESS;

        // return Command::FAILURE;
    }
}