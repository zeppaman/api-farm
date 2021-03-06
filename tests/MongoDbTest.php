<?php

use App\Services\CrudService;
use App\Util\Calculator;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;
use MongoDB\Collection;
use MongoDB\Driver\Manager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MongoDBTest extends KernelTestCase
{

    public function testDIService()
    {        
        self::bootKernel();
        $service=self::$container->get(CrudService::class);
        $this->assertNotEmpty($service);
    }

    public function testSearch()
    {
                  
        self::bootKernel();
        $service=self::$container->get(CrudService::class);
        $this->assertNotEmpty($service);

        $uk= (new DateTime())->getTimestamp();

        for( $i=0; $i<10; $i++)
        {
            $data= array(
                "title" => "mytitle  $uk",
                "body" =>"mybody  $uk"
            );

            $result=$service->add("test","test",$data);
        }

        for( $i=0; $i<10; $i++)
        {
            $data= array(
                "title" => "mytitle  $uk ALTERNATIVE",
                "body" =>"mybody  $uk"
            );

            $result=$service->add("test","test",$data);
        }

        $items=$results=$service->find("test","test", array('title' => "mytitle  $uk ALTERNATIVE"),0,100);
        $this->assertEquals(sizeof($items), 10);

        $items=$results=$service->find("test","test", array('title' => "mytitle  $uk ALTERNATIVE"),5,3);
        $this->assertEquals(sizeof($items), 3);
    
    }

    public function testCrudService()
    {        
        self::bootKernel();
        $service=self::$container->get(CrudService::class);
        $this->assertNotEmpty($service);

        $uk= (new DateTime())->getTimestamp();

        $data= array(
            "title" => "mytitle  $uk",
            "body" =>"mybody  $uk"
        );

        $result=$service->add("test","test",$data);
        echo "\n\t>> ".print_r($result, true);
        $this->assertNotEmpty($result);
        $resultGot=$service->get("test","test",$result["_id"]);
        $this->assertEquals($result["title"],$resultGot["title"]);

        $replace= array(
            "title" => "mytitle  $uk UPDATED",
            "body" =>"mybody  $uk",
            "_id" => $resultGot["_id"]
        );

        $resultGot=$service->update("test","test",$replace, true);
        $resultGot=$service->get("test","test",$result["_id"]);
        echo "\n\t>> ".print_r($resultGot, true);
        $this->assertEquals($result["title"]." UPDATED",$resultGot["title"]);


        $resultGot=$service->delete("test","test",$result["_id"]);
        $resultGot=$service->get("test","test",$result["_id"]);
        echo "\n\t>> ".print_r($resultGot, true);
        $this->assertEmpty($resultGot);
        
    
    }



    public function testDIclient()
    {        
        self::bootKernel();
        $client=self::$container->get(Client::class);
        $this->assertNotEmpty($client);
        $this->assertNotEmpty($client->listDatabaseNames());


    }
      /** @var MongoDB\Collection */
     
    public function testConnect()
    {
        
        $client = new Client(
            'mongodb://mongo/test?retryWrites=true&w=majority'
        );
        
        $list=$client->listDatabaseNames();
        foreach($list as $name)
        {
            echo $name."\n";
        }

        $manager = $client->getManager();
        $db= $client->selectDatabase("test");                
        ///$coll= new Collection($manager,"test","coll");
        $coll= $db->selectCollection("test");
        $coll->insertOne( array("ddd" => "ddd") );


        $all=$coll->find();

        foreach($all as $doc)
        {
            echo print_r($doc)."\n";
        }

    }
}
