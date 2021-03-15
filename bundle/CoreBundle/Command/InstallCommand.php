<?php

namespace Apifarm\CoreBundle\Command;

use Apifarm\CoreBundle\Services\ICrudService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class InstallCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'apifarm:install';

    /** var  $service ICrudService */
    private  $service;

    private $dataseed=array(
        "_schema" =>[
            array (
                'name' => 'entity1',
                'db' => 'test',
                'fields' => 
                array (
                  'title' => 
                  array (
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Title',
                  ),
                  'amount' => 
                  array (
                    'type' => 'int',
                    'name' => 'amount',
                    'label' => 'Amount',
                  ),
                ),
              )
            ],      
        "_mutations"=>[
            array (
                'name' => 'test',
                'code' => ' $var=$request->get(\'var\'); return array(\'test\'=>\'foo\', \'get\'=>$var);',
              )
            ],       
        "entity1" =>[
            array (
                'title' => 'prova22m2',
                'amount' => '1000',
                'updated' => '2021-03-14T17:15:57+00:00',
              )
        ]
    );

    protected $params;
    protected $rootFolder;
    public function __construct(ICrudService $service,ParameterBagInterface $params)
    {
        $this->service=$service;
        $this->params=$params;
        parent::__construct(self::$defaultName);
        $this->rootFolder=$this->params->get('kernel.project_dir');
      
    }

    protected function configure()
    {
        $this
        
        // configure an argument
        ->addOption('adminuser', null, InputArgument::OPTIONAL, 'username for initial administator','admin')
        ->addOption('adminpassword',null, InputArgument::OPTIONAL, 'password for initial administator','admin')
        ->addOption('db-host',null, InputArgument::OPTIONAL, 'host to the server','mongo')
        ->addOption('db-port', null,InputArgument::OPTIONAL, 'mnongo port','27017')
        ->addOption('db-password',null, InputArgument::OPTIONAL, 'database password','')
        ->addOption('db-user',null, InputArgument::OPTIONAL, 'database connection user',null)
        ->addOption('populate',null, InputArgument::OPTIONAL, 'populate database', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $adminuser=$input->getOption('adminuser');
        $adminpassword=$input->getOption('adminpassword');
        $dbhost=$input->getOption('db-host');
        $dbport=$input->getOption('db-port');
        $dbpassword=$input->getOption('db-password');
        $dbuser=$input->getOption('db-user');
        $populate=$input->getOption('populate');

        $this->writeConfig($dbuser,$dbpassword,$dbhost,$dbport,$output);

        $this->writeJsConfig($output);

        $this->createAdmin($adminuser, $adminpassword,$output);
    
        $this->generareKeyPair($output);

        return Command::SUCCESS;

    }

    function generareKeyPair($output)
    {
      $private_key = openssl_pkey_new();
      openssl_pkey_export_to_file( $private_key, $this->rootFolder."/config/private.key");
      $public_key_pem = openssl_pkey_get_details($private_key)['key'];  
      file_put_contents($this->rootFolder."/config/public.key", $public_key_pem );
    }

    function createAdmin($adminuser, $adminpassword,$output)
    {
      // create admin user
      $this->service->add("config","_users",
      array (
        'username' => $adminuser,
        'newpassword' => $adminpassword,
        'nome' => 'Administrator',
      ));
    }
    function initData($output)
    {      

      $output->writeln("data initing");
      foreach( $this->dataseed as $collection=>$items)
      {
        foreach($items as $item)
        {
          $this->service->add("config",$collection,$item);
        }
      }     
      $output->writeln("data inited");
    }

    function writeJsConfig($output)
    {
      $identifier=hash('md5', random_bytes(16));
      $secret= hash('sha512', random_bytes(32));
      $this->service->add("config","_clients",array (
        'identifier' => $identifier,
        'secret' => $secret,
        'grants' => 'client_credentials,password,',
        'scopes' => '',
        'uris' => '',
        'active' => true,
      ));

      $template=\Dirname(__DIR__)."/Resources/install/config.json";
      $data=json_decode($template);
      $data["oauthData"]["identifier"]=$identifier;
      $data["oauthData"]["secret"]=$secret;
      $output->writeln("getting config js template from  $template");     
      $destination=  $this->rootFolder."/public/config.json";
      $content=json_encode($data);
      file_put_contents($destination,$content);
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
      $template=\Dirname(__DIR__)."/Resources/install/apifarm-template.yaml";
      $output->writeln("getting config template from  $template");
      $content=file_get_contents($template);
      $content = str_replace('${mongodburl}', $mongodburl, $content);
      $destination=  $this->rootFolder."/config/packages/apifarm.yaml";
      file_put_contents($destination,$content);
    }
}