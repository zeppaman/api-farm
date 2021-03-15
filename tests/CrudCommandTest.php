<?php
namespace App\Tests\Command;

use App\Services\CrudService;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class UpsertCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:crud:upsert');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
           
            'database' => 'test',
            'collection' => 'test',
            'item' => '{"filed":"value"}',

        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        echo $output;
        $this->assertStringContainsString('Add', $output);

        // ...
    }


    public function testDelete()
    {
        $kernel = static::createKernel();
        static::bootKernel();
        $application = new Application($kernel);

        $service=self::$container->get(CrudService::class);

        $data= array(
            "title" => "mytitle",
            "body" =>"mybody"
        );

        $result=$service->add("config","test",$data);

        $command = $application->find('app:crud:delete');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
           
            'database' => 'test',
            'collection' => 'test',
            'id' => $result["_id"],

        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        echo $output;
        $this->assertStringContainsString('Deleting', $output);
        $this->assertStringContainsString($result["_id"], $output);

        // ...
    }



    public function testFind()
    {
        $kernel = static::createKernel();
        static::bootKernel();
        $application = new Application($kernel);

        $service=self::$container->get(CrudService::class);

        $data= array(
            "title" => "mytitle",
            "body" =>"mybody"
        );

        $result=$service->add("test","test",$data);

        $command = $application->find('app:crud:find');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
           
            'database' => 'test',
            'collection' => 'test',
            'query' => '{"title":"mytitle"}',

        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        echo $output;
        $this->assertStringContainsString('Finding', $output);
        $this->assertStringContainsString($result["_id"], $output);

        // ...
    }
}