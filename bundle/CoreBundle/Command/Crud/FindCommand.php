<?php

namespace Apifarm\CoreBundle\Command\Crud;

use Apifarm\CoreBundle\Services\ICrudService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FindCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'apifarm:crud:find';

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
        ->addArgument('query', InputArgument::OPTIONAL, 'query',"[]")
        ->addArgument('skip', InputArgument::OPTIONAL, 'skip',0)
        ->addArgument('limit', InputArgument::OPTIONAL, 'limit ',1000)
        ->addArgument('output', InputArgument::OPTIONAL, 'output file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $db=$input->getArgument('database');
        $collection=$input->getArgument('collection');
        $query=$input->getArgument('query');
        $skip=$input->getArgument('skip');
        $limit=$input->getArgument('limit');
        $file=$input->getArgument('output');

        $output->writeln("Finding db: $db collection: $collection skip: $skip limit: $limit output: $file");

        $output->writeln("Query: $query");

        $filter=json_decode($query,true);

        $output->writeln("Parsed Query: ". print_r( $filter,true));

        if(empty($filter))
        {
            $filter=[];
        }

       
       
        $result=$this->service->find($db,$collection,$filter,$skip,$limit); 
        
        $json=json_encode($result);
        if($file)
        {
            file_put_contents($output,$json);
        }
        $output->writeln($json);


        return Command::SUCCESS;

        // return Command::FAILURE;
    }
}