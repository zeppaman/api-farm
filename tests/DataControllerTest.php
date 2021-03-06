<?php
namespace App\Tests;

use App\Services\CrudService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class DataControllerTest extends WebTestCase
{
    public function testDataHttp()
    {
        $client = static::createClient();

        $uk= (new DateTime())->getTimestamp();

       
        $data= array(
            "title" => "mytitle  $uk",
            "body" =>"mybody  $uk"
        );


      $client->request(
            'POST',
            '/api/data/test/test',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response=$client->getResponse();

       // echo "\n\t>> ".print_r($response,true);
       

        $this->assertTrue($response->headers->contains(
            'Content-Type', 'application/json'
        ));
        $this->assertResponseIsSuccessful();

        $responseJson = json_decode($response->getContent(), true);
        $item = $responseJson["data"];
        $id=$item["_id"];
        $data["title"]="updated";

        $client->request(
            'PUT',
            "/api/data/test/test/$id",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
        $response=$client->getResponse();
        
        $this->assertResponseIsSuccessful();
        $responseJson = json_decode($response->getContent(), true);
        $this->assertTrue($response->headers->contains(
            'Content-Type', 'application/json'
        ));
        $updated=$responseJson["data"];
        $this->assertEquals($updated["title"], "updated");


        $client->request(
            'GET',
            "/api/data/test/test/$id",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            null
        );

        $response=$client->getResponse();
        
        $this->assertResponseIsSuccessful();
        $responseJson = json_decode($response->getContent(), true);

        $this->assertEquals($id, $responseJson["data"]["_id"]);


        $client->request(
            'DELETE',
            "/api/data/test/test/$id",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            null
        );

        $this->assertResponseIsSuccessful();

        $client->request(
            'GET',
            "/api/data/test/test/$id",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            null
        );

        $response=$client->getResponse();
        $responseJson = json_decode($response->getContent(), true);
        $this->assertEmpty( $responseJson["data"] );

    }
}