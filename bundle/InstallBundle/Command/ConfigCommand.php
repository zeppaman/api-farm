<?php

namespace Apifarm\InstallBundle\Command;

use Apifarm\CoreBundle\Services\ICrudService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ConfigCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'apifarm:config';

    protected function configure()
    {
        $this
        
        // configure an argument       
        ->addOption('db-host',null, InputArgument::OPTIONAL, 'host to the server','mongo')
        ->addOption('db-port', null,InputArgument::OPTIONAL, 'mnongo port','27017')
        ->addOption('db-password',null, InputArgument::OPTIONAL, 'database password','')
        ->addOption('db-user',null, InputArgument::OPTIONAL, 'database connection user',null);
    }


    protected $params;
    protected $rootFolder;

    public function setRootFolder($rootFolder)
    {
        $this->rootFolder=$rootFolder;
    }

    public function __construct()
    {
        
        parent::__construct(self::$defaultName);
      
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->rootFolder);      

        $dbhost=$input->getOption('db-host');
        $dbport=$input->getOption('db-port');
        $dbpassword=$input->getOption('db-password');
        $dbuser=$input->getOption('db-user');

        $this->writeConfig($dbuser,$dbpassword,$dbhost,$dbport,$output);
        return Command::SUCCESS;
    }

    function writeConfig($dbuser,$dbpassword,$dbhost,$dbport,$output)
    {
      // 
      $credential="";

      if($dbuser)
      {
        $credential="$dbuser:$dbpassword@";
      }

      $mongodburl="mongodb://$credential$dbhost:$dbport/?retryWrites=true&w=majority";
      $template=\Dirname(__DIR__)."/install/apifarm-template.yaml";
      $output->writeln("getting config template from  $template");
      $content=file_get_contents($template);
      $content = str_replace('${mongodburl}', $mongodburl, $content);
      $destination=  $this->rootFolder."/config/packages/apifarm.yaml";
      file_put_contents($destination,$content);
    }
}