<?php

namespace App\Command;

use App\Services\ICrudService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'apifarm:install';

    /** var  $service ICrudService */
    private  $service;

    private $dateseed=array(
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
      "_clients" =>[
        array (
            'identifier' => 'c0a71bf0379c66c46da3ed41a4f4aab2',
            'secret' => 'e8f9855c30bb9915e61bc093656e75c7e8e3bc3b221eca2be796790af96e6f347b738a28c84ffb9db61617e812b21c51c8b29d9d8d1c92d2df9b386a2404c394',
            'grants' => 'client_credentials,password,',
            'scopes' => '',
            'uris' => '',
            'active' => true,
          )
        ],
        "_mutations"=>[
            array (
                'name' => 'test',
                'code' => ' $var=$request->get(\'var\'); return array(\'test\'=>\'foo\', \'get\'=>$var);',
              )
            ],
        "_users"=>[
            array (
                'username' => 'bob',
                'password' => 'RO7l+Rap5E0CHSEV1AQWuK9QBpz0s1R4PfHIg3NaWUfztR5L+5nwxw==',
                'nome' => 'Admin user',
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

    public function __construct(ICrudService $service)
    {
        $this->service=$service;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this
        // configure an argument
        ->addArgument('adminuser', InputArgument::REQUIRED, 'username for initial administator')
        ->addArgument('adminpassword', InputArgument::REQUIRED, 'password for initial administator')
        ->addArgument('db-host', InputArgument::OPTIONAL, 'host to the server','mongo')
        ->addArgument('db-port', InputArgument::OPTIONAL, 'mnongo port','27017')
        ->addArgument('db-password', InputArgument::OPTIONAL, 'initial password','xyz')
        ->addArgument('db-user', InputArgument::OPTIONAL, 'initial admin username','admin');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //setup db
        $config=array();

        // create user

        //data init
        foreach( $this->dataseed as $key=>$value)
        {

        }     

        //create config.json

        return Command::SUCCESS;

        // return Command::FAILURE;
    }
}