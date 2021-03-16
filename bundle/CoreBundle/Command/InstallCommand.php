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
                'title' => 'test item',
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
        $populate=$input->getOption('populate');      

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
      $output->writeln("<info>Private and public key generated</info>");
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

      $output->writeln("<info>Admin user $adminuser created. You can login with it. </info>");
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
      $output->writeln("<info>data inited</info>");
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
      $output->writeln("<comment>Js read from  $template </comment>");
      $content=file_get_contents($template);
      $output->writeln($content);
      $data=json_decode($content,true);
      $data["oauthData"]["identifier"]=$identifier;
      $data["oauthData"]["secret"]=$secret;   
      $destination=  $this->rootFolder."/public/config.json";
      $content=json_encode($data,JSON_PRETTY_PRINT);
      file_put_contents($destination,$content);

      $output->writeln("<info>Js config created in /public/config.js You can change with later. </info>");
    }  
}