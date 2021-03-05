<?php

use App\Util\Calculator;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;
use MongoDB\Collection;
use MongoDB\Driver\Manager;

class MongoDBTest extends TestCase
{

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
