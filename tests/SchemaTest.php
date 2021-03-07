<?php

use App\Services\CrudService;
use App\Util\Calculator;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;
use MongoDB\Collection;
use MongoDB\Driver\Manager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SchemaTest extends KernelTestCase
{
    public function testSchema()
    {
                  
        self::bootKernel();
        $service=self::$container->get(CrudService::class);
        $this->assertNotEmpty($service);

        
        $uk= (new DateTime())->getTimestamp();

        $data= array(
            "name" => "Entity1 $uk",
            "db" =>"test",
            "fields" =>
            [
                "title"=>array(
                    "type" =>"text",
                    "name" =>"title",
                    "label" => "Title",
                ),
                "amount"=>array(
                    "type" =>"int",
                    "name" =>"amount",
                    "label" => "Amount",
                )
            ]
        );

        $result=$service->add("test","_schema",$data);

        $data["name"]="Entity2 $uk";

        $result=$service->add("test","_schema",$data);

        $schema= $service->getSchema("test");

        echo "\n\n schema all".print_r($schema, true);

        $schema= $service->getSchema("test","Entity1");
        echo "\n\n schema entity".print_r($schema, "Entity1");


    }
}